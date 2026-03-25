<?php

declare(strict_types=1);

namespace App\Modules\Auth\Presentation\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\UseCases\LoginUseCase;
use App\Modules\Auth\Application\UseCases\LogoutUseCase;
use App\Modules\Auth\Application\UseCases\RegisterUseCase;
use App\Modules\Auth\Domain\ValueObject\LoginCredentialsValueObject;
use App\Modules\Auth\Domain\ValueObject\RegisterValueObject;
use App\Modules\Auth\Presentation\Requests\LoginFormRequest;
use App\Modules\Auth\Presentation\Requests\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Str;

final class AuthController extends Controller
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private LogoutUseCase $logoutUseCase,
        private RegisterUseCase $registerUseCase,
    ) {}

    public function showLoginPage(): View
    {
        return view('auth.login');
    }

    /**
     * Called when client submits the payment form.
     */
    public function login(LoginFormRequest $request)
    {
        $key = Str::lower($request->input('email')) . '@' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            throw ValidationException::withMessages([
                'Login' => 'Too many attempts. Try again soon.',
            ]);
        }

        $credentials = new LoginCredentialsValueObject(
            email: $request->email,
            password: $request->password,
            remember: $request->remember,
        );

        $isAuthenticated = $this->loginUseCase->execute($credentials, $request->ip());

        if ($isAuthenticated) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success');
        }

        RateLimiter::hit($key, 60);

        return back(status: 400)
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $this->logoutUseCase->execute((string) $request->ip());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }

    public function register(RegisterRequest $request)
    {
        $credentials = new RegisterValueObject(
            name: $request->name,
            email: $request->email,
            password: $request->password,
        );

        $isRegistered = $this->registerUseCase->execute($credentials, $request->ip());

        if ($isRegistered) {
            $credentials = new LoginCredentialsValueObject(
                email: $request->email,
                password: $request->password,
            );
            dump($credentials);

            $isAuthenticated = $this->loginUseCase->execute($credentials, $request->ip());

            dump($isAuthenticated);

            if ($isAuthenticated) {
                $request->session()->regenerate();

                return redirect()->intended('/')->with('success', 'Welcome! Your account has been created.');
            }
        }

        return back(status: 500)
            ->withErrors(['Error' => 'Error on registing.']);
    }
}

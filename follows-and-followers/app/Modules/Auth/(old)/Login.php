<?php

declare(strict_types=1);

namespace App\Modules\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class Login extends Controller
{
    public function __invoke(Request $req)
    {
        $key = Str::lower($req->input('email')) . '@' . $req->ip();

        $credentials = $req->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $req->boolean('remember'))) {
            RateLimiter::clear($key);
            $req->session()->regenerate();

            return redirect()->intended('/')->with('success');
        }

        RateLimiter::hit($key, 60);

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->onlyInput('email');
    }
}

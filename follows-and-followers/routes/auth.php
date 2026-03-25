<?php

declare(strict_types=1);

use App\Modules\Auth\Presentation\Controller\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/register', 'auth.register')
    ->name('register.view');

Route::view('/login', 'auth.login')
    ->name('login.view');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

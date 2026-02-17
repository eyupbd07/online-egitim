<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController; // Bu import olmalı
use App\Http\Controllers\Auth\RegisteredUserController;
// ... (diğer importlar)
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');
    // ... (diğer rotalar)

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');
    // ... (diğer rotalar)

    // --- BU ROTA ÖNEMLİ ---
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');
    // --- ROTA KONTROLÜ SONU ---

    // ... (diğer rotalar)
});

// ... (diğer rotalar)

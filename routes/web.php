<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Sign In Page
Route::get('/', function () {
    return Inertia::render('Auth/SignIn');
});

// Forgot Password Page
Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
});

// Reset Password Page
Route::get('/reset-password', function () {
    return Inertia::render('Auth/ResetPassword');
});

// Two-Factor Authentication Page
Route::get('/2fa-verification', function () {
    return Inertia::render('Auth/TwoFactorVerification');
});

// Lock Screen Page
Route::get('/lock-screen', function () {
    return Inertia::render('Auth/LockScreen');
});

// Security Pin Page
Route::get('/security-pin', function () {
    return Inertia::render('Auth/SecurityPin');
});

// New Device Notification Page
Route::get('/new-device-notification', function () {
    return Inertia::render('Auth/NewDeviceNotification');
});

Route::prefix('portals')->group(function () {
    Route::get('/admin', fn () =>
        Inertia::render('portals/Admin/Index')
    );

    Route::get('/client', fn () =>
        Inertia::render('portals/Client/Index')
    );

    Route::get('/field', fn () =>
        Inertia::render('portals/Field/Index')
    );

    Route::get('/specialist', fn () =>
        Inertia::render('portals/Specialist/Index')
    );

    Route::get('/super-admin', fn () =>
        Inertia::render('portals/SuperAdmin/Index')
    );

    Route::get('/supervisor', fn () =>
        Inertia::render('portals/Supervisor/Index')
    );

    Route::get('/support', fn () =>
        Inertia::render('portals/Support/Index')
    );

    Route::get('/tele-supervisor', fn () =>
        Inertia::render('portals/TeleSupervisor/Index')
    );
});

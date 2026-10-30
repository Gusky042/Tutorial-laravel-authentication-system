<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    // GET and POST share the URL /login, so only the GET route carries the
    // name 'login'. The form posts to route('login'). Two routes with the same
    // name would break `php artisan route:cache`.
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // TEMPORARY - replaced in Phase 6
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// TEMPORARY - replaced in Phase 3
Route::view('/login', 'auth.login')->name('login');

// TEMPORARY - replaced in Phase 6
Route::view('/dashboard', 'dashboard')->name('dashboard');

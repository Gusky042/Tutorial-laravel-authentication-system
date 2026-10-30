<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Create the account and log the new user in.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // validated() holds only name, email and password. The User model's
        // 'hashed' cast hashes the plain password once, so no Hash::make here.
        $user = User::create($request->validated());

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        // The key must exist with a null value. DashboardController treats a
        // missing key as a "remember me" visit and would show last_login_at
        // (the sign-up time) instead of "This is your first login".
        $request->session()->put('previous_login_at', null);

        // last_login_at is not fillable, so it is set directly.
        $user->last_login_at = now();
        $user->save();

        return redirect()->route('dashboard')->with('status', 'Welcome! Your account has been created.');
    }
}

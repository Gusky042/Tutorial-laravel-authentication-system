<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Same message whether or not the email exists, so registered emails cannot be discovered.
     */
    public const NEUTRAL_STATUS = 'If an account exists for that email, we have sent a password reset link.';

    /**
     * Show the "forgot password" form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link if the email belongs to a user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // The result is deliberately ignored. The broker only reports RESET_THROTTLED
        // for emails that belong to a user, so showing it would reveal which emails
        // are registered. Flooding is stopped by the route's throttle:6,1 middleware.
        Password::sendResetLink($request->only('email'));

        return back()->with('status', self::NEUTRAL_STATUS);
    }
}

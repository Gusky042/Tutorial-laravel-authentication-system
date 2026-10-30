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

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_THROTTLED) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        return back()->with('status', self::NEUTRAL_STATUS);
    }
}

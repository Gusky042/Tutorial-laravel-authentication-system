<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function edit(): View
    {
        return view('settings.password');
    }

    /**
     * Change the logged-in user's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $newPassword = $request->validated('password');

        $request->user()->update([
            'password' => Hash::make($newPassword),
        ]);

        // Re-hashes the password so the hash stored in every other session no longer
        // matches; the 'auth.session' middleware then logs those sessions out.
        // It also refreshes this device's "remember me" cookie.
        Auth::logoutOtherDevices($newPassword);

        $request->session()->regenerate();

        return redirect()->route('password.edit')->with('status', 'Your password has been changed.');
    }
}

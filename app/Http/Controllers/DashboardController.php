<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the dashboard for the logged-in user.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        // LoginController::store always sets 'previous_login_at' (null on a first login).
        // If the key is missing, the user came back through a "remember me" cookie
        // without the login form, so the stored last_login_at is the best value.
        $previousLoginAt = $request->session()->exists('previous_login_at')
            ? $request->session()->get('previous_login_at')
            : $user->last_login_at?->toIso8601String();

        return view('dashboard', [
            'user' => $user,
            'previousLoginAt' => $previousLoginAt ? Carbon::parse($previousLoginAt) : null,
        ]);
    }
}

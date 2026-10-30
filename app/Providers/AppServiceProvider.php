<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Single source of password rules. Use Password::defaults() wherever a password is validated.
        Password::defaults(function () {
            $rule = Password::min(8)->mixedCase()->numbers()->symbols();

            return $this->app->isProduction() ? $rule->uncompromised() : $rule;
        });

        // Build reset links from APP_URL, not from the request's Host header,
        // so a forged Host header cannot point the emailed link at another site.
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return rtrim(config('app.url'), '/').route('password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], absolute: false);
        });
    }
}

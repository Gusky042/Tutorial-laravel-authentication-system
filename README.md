# Laravel Auth App

A small Laravel 13 application with hand-written authentication: sign up, log in, log out, forgot password (email reset link), reset password, change password, and a protected dashboard.

New users can create their own account at `/register`. Administrators can still add users from the command line with `php artisan user:create`.

Built with Blade, Tailwind CSS (through Vite) and MySQL. No starter kit (Breeze, Jetstream or Fortify) is used, so every controller, request and view is in this repository.

## Requirements

- PHP 8.3 or newer, with the `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl` and `fileinfo` extensions
- Composer 2
- Node.js 20.19+ or 22.12+, and npm
- MySQL 8 (utf8mb4)

The test suite runs on an in-memory SQLite database (`pdo_sqlite`), so tests never touch MySQL.

## Local setup

1. Clone the repository and enter it.

   ```bash
   git clone https://github.com/Gusky042/Tutorial-laravel-authentication-system.git
   cd Tutorial-laravel-authentication-system
   ```

2. Install PHP and JavaScript dependencies.

   ```bash
   composer install
   npm install
   ```

3. Create your `.env` file and an application key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell use `Copy-Item .env.example .env` instead of `cp`.

4. Create the MySQL database.

   ```sql
   CREATE DATABASE laravel_auth_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

5. Set `DB_USERNAME` and `DB_PASSWORD` in `.env` for your MySQL server. The other `DB_*` values already point to `127.0.0.1:3306` and `laravel_auth_app`.

6. Create the tables and the test user.

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

   The seeder creates **test@example.com / Password123!**. It only runs when `APP_ENV` is `local` or `testing`.

7. Build the front-end assets and start the server.

   ```bash
   npm run build
   php artisan serve
   ```

   Open <http://localhost:8000> and log in with the test user.

### Reading password reset emails locally

`.env.example` sets `MAIL_MAILER=log`, so emails are written to `storage/logs/laravel.log` instead of being sent. After you request a reset, open that file and copy the `/reset-password/...` link.

### Note for WAMP and other MyISAM servers

Some local MySQL installs (WAMP, for example) use MyISAM as the default storage engine. This project forces InnoDB in `config/database.php`, so migrations work on those servers without changing `my.ini`.

## Create a user

**Sign up in the browser:** open <http://localhost:8000/register> (or click "Create an account" on the login page). After signing up you are logged in and taken to the dashboard. Emails are stored in lowercase and must be unique.

**As an administrator:** create an account from the command line.

```bash
php artisan user:create
```

The command asks for a name, an email and a password (the password is hidden as you type). It rejects an invalid or already-used email and a password that does not meet the password rules.

## Password rules

Passwords must have at least 8 characters, upper and lower case letters, a number and a symbol. In production they are also checked against known data breaches. The rules are set once in `app/Providers/AppServiceProvider.php` with `Password::defaults()`.

## Run the tests

```bash
php artisan test
```

## Routes

| Method | URI | Name | Middleware |
| --- | --- | --- | --- |
| GET | `/` | – | web (redirects to login or dashboard) |
| GET | `/login` | `login` | guest |
| POST | `/login` | – | guest, 5 failed attempts per email + IP |
| GET | `/register` | `register` | guest |
| POST | `/register` | `register.store` | guest, throttle:6,1 |
| POST | `/logout` | `logout` | auth, auth.session |
| GET | `/forgot-password` | `password.request` | guest |
| POST | `/forgot-password` | `password.email` | guest, throttle:6,1 |
| GET | `/reset-password/{token}` | `password.reset` | guest |
| POST | `/reset-password` | `password.store` | guest, throttle:6,1 |
| GET | `/settings/password` | `password.edit` | auth, auth.session |
| PUT | `/settings/password` | `password.update` | auth, auth.session, throttle:6,1 |
| GET | `/dashboard` | `dashboard` | auth, auth.session |

## Production checklist

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, and the correct `APP_URL` with `https://`. Password reset links are built from `APP_URL`.
- [ ] Real MySQL credentials for a database user that has only the privileges the app needs on its own database (not `root`).
- [ ] Real `MAIL_*` settings (SMTP or a mail service), and a test password reset email sent and received.
- [ ] `SESSION_SECURE_COOKIE=true` when the site is served over https.
- [ ] `php artisan migrate --force`
- [ ] `npm run build`
- [ ] `php artisan optimize` (caches config, routes and views). Run it again after every deploy.
- [ ] `storage/` and `bootstrap/cache/` are writable by the web server user.
- [ ] Do **not** run the test-user seeder in production. Create real users with `php artisan user:create`.

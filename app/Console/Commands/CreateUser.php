<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class CreateUser extends Command
{
    /**
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * @var string
     */
    protected $description = 'Create a new user from the command line';

    public function handle(): int
    {
        $data = [
            'name' => trim((string) $this->ask('Name')),
            'email' => Str::lower(trim((string) $this->ask('Email'))),
            'password' => (string) $this->secret('Password'),
        ];

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $this->info("User {$user->email} created (id {$user->id}).");

        return self::SUCCESS;
    }
}

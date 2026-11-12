<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('ADMIN_NAME');
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $name || ! $email || ! $password) {
            $message = 'ADMIN_NAME, ADMIN_EMAIL, and ADMIN_PASSWORD must be set before seeding the admin account.';

            if (app()->environment('testing')) {
                return;
            }

            throw new \RuntimeException($message);
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'email_verified_at' => now(),
            ],
        );
    }
}

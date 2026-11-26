<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Creates the initial Super Administrator ONCE (never overwrites an existing account).
 * Credentials come from .env (ADMIN_NAME / ADMIN_EMAIL / ADMIN_PASSWORD). If no password
 * is configured a random one is generated and printed. The account must change its
 * password at first login.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@orisa.test');

        if (User::where('email', $email)->exists()) {
            return;
        }

        $generated = ! env('ADMIN_PASSWORD');
        $password = env('ADMIN_PASSWORD') ?: Str::password(20, symbols: false);

        User::create([
            'role_id' => Role::where('slug', 'super-admin')->value('id'),
            'name' => env('ADMIN_NAME', 'Site Administrator'),
            'email' => $email,
            'password' => $password,
            'must_change_password' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        if ($this->command) {
            $this->command->warn("Initial administrator created: {$email}");
            if ($generated) {
                $this->command->warn("Generated password (shown once): {$password}");
            }
            if (app()->isProduction()) {
                $this->command->warn('Production: change this password immediately after first login.');
            } else {
                $this->command->warn('DEV account: password change is forced at first login.');
            }
        }
    }
}

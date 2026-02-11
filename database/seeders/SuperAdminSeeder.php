<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $isProd = app()->environment('production');

        $defaultEmail = $isProd ? null : 'superadmin@example.test';
        $defaultPass  = $isProd ? null : 'superadmin123';

        $name = env('SUPER_ADMIN_NAME', 'Super Admin');
        $email = env('SUPER_ADMIN_EMAIL', $defaultEmail);
        $pass  = env('SUPER_ADMIN_PASSWORD', $defaultPass);

        $forceReset = filter_var(env('SUPER_ADMIN_FORCE_RESET', false), FILTER_VALIDATE_BOOL);

        // In production, require explicit env values (no accidental default admin)
        if (!$email || !$pass) {
            return;
        }

        $user = User::firstOrNew(['email' => $email]);

        $isNew = !$user->exists;

        // Always ensure correct flags + name
        $user->fill([
            'name' => $name,
            'is_admin' => true,
            'is_super_admin' => true,
        ]);

        // Only set/reset password when needed
        if ($isNew || $forceReset) {
            $user->password = Hash::make($pass);
        }

        $user->save();
    }
}

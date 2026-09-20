<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();

        // All public content sourced from the ELDESCO presentation.
        $this->call(PresentationContentSeeder::class);
    }

    private function seedAdmin(): void
    {
        $email = env('ELDESCO_ADMIN_EMAIL', 'admin@eldesco.am');
        $password = env('ELDESCO_ADMIN_PASSWORD', 'change-me-before-production');

        if (app()->environment('production') && $password === 'change-me-before-production') {
            $this->command?->warn('ELDESCO admin was not seeded: set ELDESCO_ADMIN_PASSWORD in production.');

            return;
        }

        if (app()->environment('production')) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'ELDESCO Admin',
                    'password_hash' => Hash::make($password),
                    'role' => 'admin',
                ]
            );

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'ELDESCO Admin',
                'password_hash' => Hash::make($password),
                'role' => 'admin',
            ]
        );
    }
}

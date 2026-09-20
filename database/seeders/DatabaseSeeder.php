<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedTeam();

        // Pages, menus, site settings, services and projects.
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

    private function seedTeam(): void
    {
        if (TeamMember::query()->exists()) {
            return;
        }

        TeamMember::create([
            'name_hy' => 'Վահե Պարսամյան',
            'name_en' => 'Vahe Parsamyan',
            'name_ru' => 'Ваге Парсамян',
            'position_hy' => 'Տնօրեն',
            'position_en' => 'Director',
            'position_ru' => 'Директор',
            'order_index' => 1,
        ]);

        TeamMember::create([
            'name_hy' => 'Վահրամ Կիկոյան',
            'name_en' => 'Vahram Kikoyan',
            'name_ru' => 'Ваграм Кикоян',
            'position_hy' => 'Գլխավոր հաշվապահ',
            'position_en' => 'Chief Accountant',
            'position_ru' => 'Главный бухгалтер',
            'order_index' => 2,
        ]);
    }
}

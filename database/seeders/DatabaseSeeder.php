<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name'     => 'デモユーザー',
                'email'    => 'demo@example.com',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            TermSeeder::class,
            AdditionalTermSeeder::class,
            AdditionalTermSeeder2::class,
            AdditionalTermSeeder3::class,
            RequestedTermSeeder::class,
        ]);
    }
}

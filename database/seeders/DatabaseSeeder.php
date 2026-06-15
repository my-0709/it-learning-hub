<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        // 初回のみ実行（再デプロイ時は既存データを保持）
        if (DB::table('categories')->count() === 0) {
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
}

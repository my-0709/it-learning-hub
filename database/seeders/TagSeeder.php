<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'ITパスポート', '基本情報', '応用情報', 'AWS', 'Linux',
            '初級', '中級', '上級', 'プロトコル', '暗号',
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tag, '-')],
                ['name' => $tag, 'slug' => \Illuminate\Support\Str::slug($tag, '-')]
            );
        }
    }
}

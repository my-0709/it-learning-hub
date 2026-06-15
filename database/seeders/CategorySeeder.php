<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'ネットワーク',       'slug' => 'network',    'description' => 'TCP/IP・HTTP・DNS等のネットワーク技術',    'color' => '#38BDF8'],
            ['name' => 'セキュリティ',       'slug' => 'security',   'description' => '暗号化・認証・脅威対策等のセキュリティ技術', 'color' => '#F87171'],
            ['name' => 'データベース',       'slug' => 'database',   'description' => 'SQL・正規化・トランザクション等のDB技術',   'color' => '#34D399'],
            ['name' => 'アルゴリズム',       'slug' => 'algorithm',  'description' => 'ソート・探索・計算量等のアルゴリズム',       'color' => '#A78BFA'],
            ['name' => 'クラウド',           'slug' => 'cloud',      'description' => 'AWS・GCP・Azure等のクラウドサービス',       'color' => '#FB923C'],
            ['name' => 'プログラミング',     'slug' => 'programming','description' => 'オブジェクト指向・設計パターン等',           'color' => '#FBBF24'],
            ['name' => 'OS・Linux',          'slug' => 'os-linux',   'description' => 'プロセス管理・ファイルシステム・コマンド等', 'color' => '#6EE7B7'],
            ['name' => 'Webテクノロジー',    'slug' => 'web',        'description' => 'HTTP・REST・Cookie等のWeb技術',             'color' => '#93C5FD'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}

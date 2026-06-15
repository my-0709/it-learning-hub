<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ローカル（SQLite）では既に実行済みのためスキップ
        if (!Schema::hasTable('term_tag')) {
            Schema::create('term_tag', function (Blueprint $table) {
                $table->foreignId('term_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
                $table->primary(['term_id', 'tag_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('term_tag');
    }
};

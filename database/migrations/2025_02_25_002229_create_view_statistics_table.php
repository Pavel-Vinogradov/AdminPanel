<?php

declare(strict_types=1);


use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::connection('mongodb')->create('view_statistics', static function (Blueprint $table): void {
            $table->id();
            $table->integer('article_id');
            $table->string('view_at');
            $table->integer('user_id');
            $table->string('ip_address');
            $table->string('browser');
            $table->string('comment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('view_statistics');
    }
};

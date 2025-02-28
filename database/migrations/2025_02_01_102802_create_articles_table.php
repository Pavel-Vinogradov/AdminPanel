<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('articles', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->comment('Таблица статьей');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

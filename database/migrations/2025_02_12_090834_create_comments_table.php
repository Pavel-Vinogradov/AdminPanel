<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::connection('mongodb')->create('comments', function (Blueprint $collection): void {
            $collection->id();
            $collection->integer('article_id');
            $collection->integer('user_id');
            $collection->text('body');
            $collection->integer('parent_id')->nullable();
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->drop('comments');
    }
};

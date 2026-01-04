<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('story_pages', function (Blueprint $table) {
            // Если есть старая колонка 'image', удаляем её, чтобы не мешалась
            if (Schema::hasColumn('story_pages', 'image')) {
                $table->dropColumn('image');
            }

            // Создаем новую колонку image_id (без ->change())
            if (!Schema::hasColumn('story_pages', 'image_id')) {
                $table->unsignedBigInteger('image_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('story_pages') && Schema::hasColumn('story_pages', 'image_id')) {
            Schema::table('story_pages', function (Blueprint $table) {
                $table->renameColumn('image_id', 'image');

                $table->foreign('image')->references('id')->on('attachments');
            });
        }
    }
};

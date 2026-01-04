<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('faq')) {
            Schema::create('faq', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('faq_category_id');
                $table->string('title');
                $table->string('slug');
                $table->text('text');
                $table->unsignedInteger('sort');
                $table->boolean('active')->default(false);
                $table->timestamps();

                $table->index(['faq_category_id']);

                $table->foreign('faq_category_id')
                    ->references('id')
                    ->on('faq_categories')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('faq') && Schema::hasColumn('faq', 'faq_category_id')) {
            Schema::table('faq', function (Blueprint $table) {
               $table->dropForeign('faq_faq_category_id_foreign');
            });
        }

        Schema::dropIfExists('faq');
    }
}

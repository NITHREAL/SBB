<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddH1ColumnToMetaTagValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('meta_tag_values', static function (Blueprint $table) {
            $table->string('header_one')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('meta_tag_values', static function (Blueprint $table) {
            $table->dropColumn('header_one');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortFieldInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('sort')
                ->after('delivery_in_country')
                ->unsigned()
                ->default(500);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->integer('sort')
                ->after('title')
                ->unsigned()
                ->default(500);
        });

        Schema::table('farmers', function (Blueprint $table) {
            $table->integer('sort')
                ->after('description')
                ->unsigned()
                ->default(500);
        });

        Schema::table('slides', function (Blueprint $table) {
            $table->integer('sort')
                ->after('url')
                ->unsigned()
                ->default(500);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sort');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('sort');
        });

        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('sort');
        });

        Schema::table('slides', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
}

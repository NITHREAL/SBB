<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->constrained();
            $table->boolean('active')->default(false);
            $table->string('user_type')->default('all');
            $table->string('button_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropConstrainedForeignId('city_id');
            $table->dropColumn('active');
            $table->dropColumn('user_type');
            $table->dropColumn('button_text');
        });
    }
}

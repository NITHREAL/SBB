<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('is_main');
            $table->dropConstrainedForeignId('city_id');
            $table->boolean('active')->default(true);
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropColumn('type');
            $table->foreignId('city_id')->nullable()->constrained();
            $table->boolean('is_main')->default(false);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLegalEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_entities', function (Blueprint $table) {
            $table->string('system_id')->after('id')->unique();
            $table->string('short_title')->after('title')->nullable();
            $table->string('full_title')->after('short_title')->nullable();
            $table->string('first_name')->after('full_title')->nullable();
            $table->string('second_name')->after('first_name')->nullable();
            $table->string('last_name')->after('second_name')->nullable();
            $table->string('certificate')->after('sber_password')->nullable();
            $table->date('certificate_date')->after('certificate')->nullable();
            $table->string('inn')->after('certificate_date')->nullable();
            $table->string('ogrn')->after('inn')->nullable();
            $table->string('okato')->after('ogrn')->nullable();
            $table->string('okpo')->after('okato')->nullable();

            $table->text('hash_key')->nullable()->after('sber_password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_entities', function (Blueprint $table) {
            $table->dropColumn('system_id');
            $table->dropColumn('short_title');
            $table->dropColumn('full_title');
            $table->dropColumn('first_name');
            $table->dropColumn('second_name');
            $table->dropColumn('last_name');
            $table->dropColumn('certificate');
            $table->dropColumn('certificate_date');
            $table->dropColumn('inn');
            $table->dropColumn('ogrn');
            $table->dropColumn('okato');
            $table->dropColumn('okpo');
            $table->dropColumn('hash_key');
        });
    }
}

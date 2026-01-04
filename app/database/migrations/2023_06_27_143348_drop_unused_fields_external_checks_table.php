<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedFieldsExternalChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_checks', function (Blueprint $table) {
            $table->dropForeign('journal_bonuses_user_id_foreign');
            $table->dropForeign('journal_bonuses_store_id_foreign');
            $table->dropColumn('user_id');
            $table->dropColumn('store_id');
            $table->dropColumn('sale_time');
            $table->dropColumn('sum_purchase');
            $table->dropColumn('amount_bonus');
            $table->dropColumn('number_check');
            $table->dropColumn('description_check');
            $table->dropColumn('gift');
            $table->dropColumn('name_operation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->comment('Пользователь, ссылка на users');
            $table->unsignedBigInteger('store_id')->nullable()->comment('Магазин, ссылка на stores');
            $table->dateTime('sale_time')->nullable()->index()->comment('Дата и время продажи');
            $table->double('sum_purchase', 22,2)->nullable()->index()->comment('Сумма покупки');
            $table->double('amount_bonus', 22,2)->nullable()->index()->comment('Количество бонусов');
            $table->string('number_check')->nullable()->index()->comment('Номер чека');
            $table->string('description_check', 512)->nullable()->index()->comment('Примечание к чеку');
            $table->string('gift')->nullable()->index()->comment('Подарок');
            $table->string('name_operation', 32)->nullable()->index()->comment('Название операции');
        });
    }
}

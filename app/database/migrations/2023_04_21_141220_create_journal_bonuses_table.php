<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_bonuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('Пользователь, ссылка на users');
            $table->unsignedBigInteger('store_id')->nullable()->comment('Магазин, ссылка на stores');
            $table->dateTime('sale_time')->nullable()->index()->comment('Дата и время продажи');
            $table->double('sum_purchase', 22,2)->nullable()->index()->comment('Сумма покупки');
            $table->double('amount_bonus', 22,2)->nullable()->index()->comment('Количество бонусов');
            $table->string('number_check')->nullable()->index()->comment('Номер чека');
            $table->string('description_check', 512)->nullable()->index()->comment('Примечание к чеку');
            $table->string('gift')->nullable()->index()->comment('Подарок');
            $table->string('name_operation', 32)->nullable()->index()->comment('Название операции');
            $table->json('data_check')->nullable()->comment('Данные чека');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_bonuses');
    }
}

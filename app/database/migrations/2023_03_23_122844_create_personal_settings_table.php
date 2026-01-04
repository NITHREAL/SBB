<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('allow_notify')
                ->nullable(false)
                ->default(false);
            $table->boolean('allow_notify_email')
                ->nullable(false)
                ->default(false);
            $table->boolean('allow_notify_sms')
                ->nullable(false)
                ->default(false);
            $table->boolean('allow_phone_calls')
                ->nullable(false)
                ->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_settings');
    }
}

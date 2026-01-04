<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNewUtlLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utm_labelable', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utm_label_id');
            $table->unsignedBigInteger('labeled_id');
            $table->string('labeled_type');
        });

        $labels = DB::table('utm_labels')->get();

        Schema::drop('utm_labels');
        Schema::create('utm_labels', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        $labels->map(function ($label) {
            foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'] as $type) {
                $utm = \App\Models\UtmLabel::query()->firstOrCreate([
                    'type' => $type,
                    'value' => $label->{$type}
                ]);

                \Illuminate\Support\Facades\DB::table('utm_labelable')->insert([
                    'utm_label_id' => $utm->id,
                    'labeled_id' => $label->labeled_id,
                    'labeled_type' => $label->labeled_type
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('utm_labels');
        Schema::create('utm_labels', function (Blueprint $table) {
            $table->id();
            $table->string('labeled_type');
            $table->unsignedBigInteger('labeled_id');
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
        });

        Schema::drop('utm_labelable');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTacbleAnalyticStories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('analytic_stories');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
create table analytic_stories
(
    id              bigint unsigned auto_increment primary key,
    store_id        bigint                     not null,
    count_sales     int           default 0    not null comment 'количество продаж',
    sum_sales       double(15, 2) default 0.00 not null comment 'сумма продаж',
    average_check   double(15, 2) default 0.00 not null comment 'средний чек',
    accrued_points  int           default 0    not null comment 'начисленные баллы',
    deducted_points int           default 0    not null comment 'вычтенные баллы',
    amount_gifts    int           default 0    not null comment 'количество подарков',
    new_users       int           default 0    not null,
    date_activity   date                       not null,
    created_at      timestamp                  null,
    updated_at      timestamp                  null
) collate = utf8mb4_unicode_ci;
SQL;

        \Illuminate\Support\Facades\DB::statement($sql);
        \Illuminate\Support\Facades\DB::statement('create index analytic_stories_date_activity_index on analytic_stories (date_activity);');
        \Illuminate\Support\Facades\DB::statement('create index analytic_stories_store_id_index on analytic_stories (store_id);');
    }
}

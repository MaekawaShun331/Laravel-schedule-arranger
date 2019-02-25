<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 外部キーを作成する
        Schema::table('candidates', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 外部キーを削除
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign('candidates_schedule_id_foreign');
            // 既にスケジュールIDにindexが作成されているので自動indexは作成されていない
        });
    }
}

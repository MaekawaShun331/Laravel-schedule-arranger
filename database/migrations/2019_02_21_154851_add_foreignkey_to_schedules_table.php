<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // user_idを符号無し整数にする　外部キーを作成する
        Schema::table('schedules', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 外部キーと自動で作成されたindexを削除
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_user_id_foreign');
            $table->dropIndex('schedules_user_id_foreign');
        });
        // user_idを符号付き整数に戻す
        Schema::table('schedules', function (Blueprint $table) {
            $table->integer('user_id')->change();
        });
    }
}

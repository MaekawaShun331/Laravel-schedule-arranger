<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // user_idを符号無し整数にする　外部キーを作成する
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users');
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
        // 外部キーを削除　user_idは自動indexが作成されているので削除
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_schedule_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
            $table->dropIndex('comments_user_id_foreign');
        });
        // user_idを符号付き整数に戻す
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('user_id')->change();
        });
    }
}

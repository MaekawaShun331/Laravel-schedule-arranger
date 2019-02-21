<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyToAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // candidate_idとuser_idを符号無し整数にする　外部キーを作成する
        Schema::table('availabilities', function (Blueprint $table) {
            $table->integer('candidate_id')->unsigned()->change();
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('candidate_id')->references('id')->on('candidates');
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
        // 外部キーを削除　user_idは自動indexが作成されているので削除
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropForeign('availabilities_user_id_foreign');
            $table->dropForeign('availabilities_candidate_id_foreign');
            $table->dropIndex('availabilities_user_id_foreign');
        });
        // candidate_idとuser_idを符号付き整数に戻す
        Schema::table('availabilities', function (Blueprint $table) {
            $table->integer('user_id')->change();
            $table->integer('candidate_id')->change();
        });
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    //複数代入を許可する列
    protected $fillable = ['scheduleId', 'scheduleName', 'memo', 'createdBy'];

    //primaryKey設定
    protected $primaryKey = 'scheduleId';

    //increment無効化
    protected $incrementing = false;

    /**
     * この予定表が紐付いているユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'createdBy');
    }
}

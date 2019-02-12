<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    //複数代入を許可する列
    protected $fillable = ['candidateId', 'userId', 'availability', 'scheduleId'];

    //primaryKey設定
    protected $primaryKey = ['candidateId', 'userId'];

    //increment無効化
    protected $incrementing = false;

    /**
     * この出欠が紐付いているユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'userId');
    }

    /**
     * この出欠が紐付いている候補日を取得
     */
    public function candidate()
    {
        return $this->belongsTo('App\Candidate', 'candidateId', 'candidateId');
    }
}

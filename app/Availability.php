<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    //複数代入を許可する列
    protected $fillable = ['candidate_id', 'user_id', 'availability', 'schedule_id'];

    /**
     * この出欠が紐付いているユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * この出欠が紐付いている候補日を取得
     */
    public function candidate()
    {
        return $this->belongsTo('App\Candidate');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    //複数代入を許可する列
    protected $fillable = ['candidate_name', 'schedule_id'];

    /**
     * 候補日に紐付く出欠を取得
     */
    public function availabilities()
    {
        return $this->hasMany('App\Availability');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //複数代入を許可する列
    protected $fillable = ['scheduleId', 'userId', 'comment'];

    //primaryKey設定
    protected $primaryKey = ['scheduleId', 'userId'];

    //increment無効化
    protected $incrementing = false;

    /**
     * このコメントが紐付いているユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'userId');
    }
}

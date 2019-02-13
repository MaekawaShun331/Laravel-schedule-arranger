<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //複数代入を許可する列
    protected $fillable = ['schedule_id', 'user_id', 'comment'];

    //primaryKey設定
    protected $primaryKey = ['schedule_id', 'user_id'];

    /**
     * このコメントが紐付いているユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Schedule extends Model
{
    //複数代入を許可する列
    protected $fillable = ['schedule_name', 'memo', 'user_id'];

    //increment無効化
    public $incrementing = false;

    /**
     * この予定表が紐付いているユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * レコード作成時に'id'にUUID値を生成
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate(4)->string;
        });
    }
}

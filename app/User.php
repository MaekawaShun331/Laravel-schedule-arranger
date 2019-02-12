<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * ユーザに紐付く予定表を取得
     */
    public function schedules()
    {
        return $this->hasMany('App\Schedule', 'createdBy');
    }

    /**
     * ユーザに紐付くコメントを取得
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'userId');
    }

    /**
     * ユーザに紐付く出欠を取得
     */
    public function availabilities()
    {
        return $this->hasMany('App\Availability', 'userId');
    }
}

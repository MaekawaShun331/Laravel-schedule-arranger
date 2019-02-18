<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Schedule;

class IndexController extends Controller
{
    /**
     * インデックス画面を表示
     * ログイン時にはユーザの予定表一覧も表示する
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $user = Auth::user();
        $parameters = [];
        //ログインが行われている場合、ユーザの予定表一覧を取得・表示する
        if (Auth::check()) {
            $schedules = Schedule::where('user_id', $user->id)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            $parameters = ['user' => $user, 'schedules' => $schedules];
        }
        return view('index', $parameters);
    }
}

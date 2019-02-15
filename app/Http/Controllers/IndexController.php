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
        $title = '予定調整くん';
        $user = Auth::user();
        //ログインが行われている場合、ユーザの予定表一覧を取得・表示する
        if (!empty($user)) {
            $schedules = Schedule::where('user_id', $user->id)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            return view('index', ['title' => $title, 'user' => $user, 'schedules' => $schedules]);
        } else {
            return view('index', ['title' => $title, 'user' => $user]);
        }
    }
}

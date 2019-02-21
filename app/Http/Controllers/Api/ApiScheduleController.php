<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Candidate;
use App\Availability;

class ApiScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the availability.
     *
     * @param  request  $request
     * @param  String  $schedule_id
     * @param  int  $candidate_id
     * @return \Illuminate\Http\Response
     */
    public function availabilityUpdate(Request $request, $schedule_id, $candidate_id)
    {
        //入力チェック
        $this->validate($request, [
            'availability' => 'required|integer|max:2',
        ]);

        // ユーザIDの取得
        $user_id = Auth::user()->id;
        // リクエストから出欠を取得
        $available = $request->input('availability');
        // 出欠を更新　出欠[0:欠席, 1:？, 2:出席]
        $available = ($available + 1) % 3;

        // 渡されたスケジュールID・候補日IDの候補日データが存在するかチェック
        // schedule_idで先に絞り込む(indexは作成している)
        $candidate = Candidate::where('schedule_id', $schedule_id)
                ->where('id', $candidate_id)
                ->first();

        // 存在しない候補日の場合、404エラーを表示する
        if(empty($candidate)){
            abort(404);
        }

        // 出欠を更新する　出欠が存在しない場合は作成する
        $availability = Availability::updateOrCreate(
            ['candidate_id' => $candidate_id, 'user_id' => $user_id, 'schedule_id' => $schedule_id],
            ['availability' => $available]
        );
        return response()->json(['availability' => $availability->availability]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Schedule;
use App\Candidate;
use App\Availability;
use App\Comment;

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
        $request['schedule_id'] = $schedule_id;
        $request['candidate_id'] = $candidate_id;
        $this->validate($request, [
            'schedule_id' => 'required|string',
            'candidate_id' => 'required|integer',
            'availability' => 'required|integer|max:2',
        ]);
        // 渡されたスケジュールID・候補日IDの候補日データが存在するかチェック
        // schedule_idで先に絞り込む(indexは作成している)
        // 存在しない候補日の場合、404エラーを返す
        $candidate = Candidate::where('schedule_id', $schedule_id)
                ->where('id', $candidate_id)
                ->first();
        if(empty($candidate)){
            abort(404);
        }

        // ユーザIDの取得
        $user_id = Auth::user()->id;
        // リクエストから出欠を取得
        $available = $request->input('availability');
        // 出欠を更新　出欠[0:欠席, 1:？, 2:出席]
        $available = ($available + 1) % 3;

        // 出欠を更新する　出欠が存在しない場合は作成する
        $availability = Availability::updateOrCreate(
            ['schedule_id' => $schedule_id, 'user_id' => $user_id, 'candidate_id' => $candidate_id],
            ['availability' => $available]
        );
        return response()->json(['availability' => $availability->availability]);
    }

    /**
     * Create the comment.
     *
     * @param  request  $request
     * @param  String  $schedule_id
     * @return \Illuminate\Http\Response
     */
    public function commentCreate(Request $request, $schedule_id)
    {
        //入力チェック
        $request['schedule_id'] = $schedule_id;
        $this->validate($request, [
            'schedule_id' => 'required|string',
            'comment' => 'required|string|max:255',
        ]);
        // 渡されたスケジュールIDが存在するかチェック
        // 存在しない候補日の場合、404エラーを返す
        $schedule = Schedule::find($schedule_id);
        if(empty($schedule)){
            abort(404);
        }

        // ユーザIDの取得
        $user_id = Auth::user()->id;

        // コメントを登録する　既に存在する場合は更新する
        $comment = Comment::updateOrCreate(
            ['schedule_id' => $schedule_id, 'user_id' => $user_id],
            ['comment' => $request->input('comment')]
        );
        return response()->json(['comment' => $comment->comment]);
    }

}

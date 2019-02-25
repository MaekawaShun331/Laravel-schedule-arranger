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
     * 出欠を更新します。
     * 受け取った出欠コードによって、出欠の値を変更します。
     *   受信　　　更新
     *  0:欠席 → 1:?
     *  1:?   → 2:出席
     *  2:出席 → 0:欠席
     * ※?は「未定」の意味です。
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
        $candidate = Candidate::where('schedule_id', $schedule_id)
                ->where('id', $candidate_id)
                ->first();

        // 存在しない候補日の場合、403エラーを表示する
        if(empty($candidate)){
            abort(403);
        }

        // ユーザIDの取得
        $user_id = Auth::user()->id;
        // リクエストから出欠コードを取得し、出欠コードをカウントアップ
        $available = $request->input('availability') + 1;
        // 出欠コードが3になった場合は０に戻す
        $available = $available % 3;

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
        // ユーザIDの取得
        $user_id = Auth::user()->id;
        // パラメータの予定idを存在確認してから取得
        $schedule = Schedule::scheduleCheck($schedule_id);

        // コメントを登録する　既に存在する場合は更新する
        $comment = Comment::updateOrCreate(
            ['schedule_id' => $schedule_id, 'user_id' => $user_id],
            ['comment' => $request->input('comment')]
        );
        return response()->json(['comment' => $comment->comment]);
    }

}

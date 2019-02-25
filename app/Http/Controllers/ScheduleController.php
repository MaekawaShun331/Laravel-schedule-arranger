<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Schedule;
use App\Candidate;
use App\User;
use App\Availability;

class ScheduleController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //入力チェック
        $this->validate($request, [
            'schedule_name' => 'required|string|max:255',
            'memo' => 'required|string|max:255',
            'candidates' => 'required|string|max:255',
        ]);

        $schedule = null;
        DB::transaction(function () use ($request, &$schedule) {
            //入力された内容でスケジュールを登録する
            $schedule = Schedule::create([
                'schedule_name' => substr($request->input('schedule_name'), 0, 255),
                'memo' => $request->input('memo'),
                'user_id' => $request->user()->id
            ]);

            //入力された候補日を取り出し、改行で分割して配列にする
            $candidateNames = preg_split('/\r\n|\r|\n/', $request->input('candidates'));
            //配列となった候補日にそれぞれtrimをかける
            $candidateNames = array_map(function ($c) { return trim($c);}, $candidateNames);
            //trimをかけた上で、空白行と見なされた要素を除外する
            $candidateNames = array_filter($candidateNames, function ($c) { return $c !== "";});

            //現在時刻の取得
            $now = Carbon::now();
            //候補日をスケジュールIDと合わせてレコード化する
            $candidates = array_map(function ($c) use ($schedule, $now) {
                return [
                    'candidate_name' => $c,
                    'schedule_id' => $schedule->id,
                    'created_at' => $now,
                    'updated_at' => $now
                    ];
            }, $candidateNames);

            //入力された内容で候補日を登録する
            DB::table('candidates')->insert($candidates);
        });
        //登録した予定表を表示する
        return redirect(('schedules/' . $schedule->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // パラメータのidから予定を取得
        $schedule = Schedule::find($id);

        // 存在しない予定の場合、404エラーを返す
        if (empty($schedule)){
            abort(404);
        }

        // データベースから表示する予定に紐づく全ての出欠を取得する
        $availabilities = Availability::where('schedule_id', $schedule->id)
                        ->join('users','users.id','=','availabilities.user_id')
                        ->orderBy('users.name', 'asc')
                        ->orderBy('availabilities.candidate_id', 'asc')
                        ->get();

        // ユーザ毎出欠MapMap (キー:ユーザID, 値:出欠map(キー:候補日ID, 値:出欠)) を作成する
        $availability_map_map = []; // key: user_id, value: [key: candidate_id, value: availability]

        // ユーザMap (キー:ユーザID, 値:ユーザ) を作成する
        // 閲覧ユーザと出欠に紐づくユーザ(一度でも対象予定表に出欠を登録したユーザ)を格納する
        $user_map = []; // key: user_id, value: userオブジェクト

        // 閲覧ユーザをユーザMapに設定(既に存在する場合は上書き)
        $login_user_id = $request->user()->id;
        $user_map[$login_user_id] = [
            'is_self' =>  true,
            'user_id' =>  $login_user_id,
            'username' =>  $request->user()->name
        ];
        // 出欠データを読み込み、上記二つのMapを作成する
        if($availabilities->isNotEmpty()){
            $availabilities->each(function ($a) use (&$availability_map_map, &$user_map) {
                $user_id = $a->user_id; //TODO ちゃんと入ってるか

                // 出欠Mapの更新
                // mapの初期化 既に同一ユーザの出欠Mapが作成されていれば読み込む
                $map = array_key_exists($user_id, $availability_map_map) ? $availability_map_map[$user_id] : [];
                // 出欠Mapに出欠データ(キー:候補日ID, 値:出欠)を追加
                $map[$a->candidate_id] = $a->availability;

                // 出欠MapMapに出欠Map(キー:ユーザID, 値:出欠Map)を追加
                $availability_map_map[$user_id] = $map;

                // ユーザMapの作成
                // 既に同一ユーザが読み込まれていればスキップ
                if (!array_key_exists($user_id, $user_map)) {
                    $user_map[$user_id] = [
                        'is_self' => false , // 閲覧ユーザー自身であるかを含める
                        'user_id' => $user_id ,
                        'username' => $a->user->name
                    ];
                }
            });
        }

        // ユーザMapをユーザリストに変換
        $users = array_values($user_map);

        // 予定の候補日を取得
        $candidates = Candidate::where('schedule_id', $schedule->id)
                        ->orderBy('id', 'asc')
                        ->get();

        // 全ユーザ、全候補日で二重ループしてそれぞれの出欠の値がない場合には、「欠席」を設定する
        forEach($users as $u ) {
            $user_id = $u['user_id'];
            // 対象のユーザが出欠Mapに存在するか判定　存在すればその出欠を$mapに確保、存在しなければ空の出欠を作成
            $map = array_key_exists($user_id, $availability_map_map) ? $availability_map_map[$user_id] : [];
            forEach($candidates as $c ) {
                // 対象の候補日に出欠が登録されているか判定　存在しなければ、デフォルト値として 0:欠席 を設定
                if (!array_key_exists($c->id, $map)){
                    // mapにキー:候補日ID, 値:欠席 でデフォルト値を追加する
                    $map[$c->id] = Availability::AVAILABILE_ABSENCE;
                    // 出欠MapMapに追加
                    $availability_map_map[$user_id] = $map;
                }
            }
        }

        return view('show', [
            'user' => $request->user(),
            'schedule' => $schedule,
            'candidates' => $candidates,
            'users' => $users,
            'availability_map_map' => $availability_map_map
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

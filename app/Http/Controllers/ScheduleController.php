<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Schedule;
use App\Candidate;

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

        DB::beginTransaction();
        try {
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
            DB::commit();

            //登録した予定表を表示する
            return redirect('schedules/' . $schedule->id);

        } catch (\PDOException $e){
            //データ登録中に例外が発生した場合はロールバックを行う
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

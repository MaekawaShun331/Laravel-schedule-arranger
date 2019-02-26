@extends('layouts.app')

@section('content')
@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
  <h3>予定の編集</h3>
  <form method="POST" action="/schedules/{{ $schedule->id }}">
    {{ method_field('PATCH') }}
    {{ csrf_field() }}
    <div>
      <h5>予定名</h5>
      <input type="text" name="schedule_name" value="{{ $schedule->schedule_name }}">
    </div>
    <div>
      <h5>メモ</h5>
      <textarea name="memo">{{ $schedule->memo }}</textarea>
    </div>
    <div>
      <label>既存の候補日程</label>
      <ul>
        @foreach ($candidates as $candidate)
          <li>{{ $candidate->candidate_name }}</li>
        @endforeach
      </ul>
      <p>候補日程の追加 (改行して複数入力してください)</p>
      <textarea name="candidates"></textarea>
    </div>
    <button type="submit">以上の内容で予定を編集する</button>
  </form>
@endsection

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
  <h3 class="my-3">予定の編集</h3>
  <form method="POST" action="/schedules/{{ $schedule->id }}">
    {{ method_field('PATCH') }}
    {{ csrf_field() }}
    <div class="form-group">
      <label for="scheduleName">予定名</label>
      <input class="form-control" type="text" name="schedule_name" value="{{ $schedule->schedule_name }}">
    </div>
    <div class="form-group">
      <label for="memo">メモ</label>
      <textarea class="form-control" name="memo">{{ $schedule->memo }}</textarea>
    </div>
    <div class="form-group">
      <label>既存の候補日程</label>
      <ul class="list-group">
        @foreach ($candidates as $candidate)
          <li class="list-group-item">{{ $candidate->candidate_name }}</li>
        @endforeach
      </ul>
      <label class="my-2" for="candidates">候補日程の追加 (改行して複数入力してください)</label>
      <textarea class="form-control" name="candidates"></textarea>
    </div>
    <button class="btn btn-info" type="submit">以上の内容で予定を編集する</button>
  </form>
  <h3 class="my-3">危険な変更</h3>
  <form method="POST", action="/schedules/{{ $schedule->id }}">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <button class="btn btn-danger" type="submit">この予定を削除する</button>
  </form>
@endsection

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
  <form class="my-3" method="POST" action="/schedules">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="scheduleName">予定名</label>
      <input class="form-control" type="text" name="schedule_name">
    </div>
    <div class="form-group">
      <label for="memo">メモ</label>
      <textarea class="form-control" name="memo"></textarea>
    </div>
    <div class="form-group">
      <label for="candidates">候補日程 (改行して複数入力してください)</label>
      <textarea class="form-control" name="candidates"></textarea>
    </div>
    <button class="btn btn-info" type="submit">予定をつくる</button>
  </form>
@endsection

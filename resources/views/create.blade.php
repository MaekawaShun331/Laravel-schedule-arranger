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
  <form method="POST" action="/schedules">
    {{ csrf_field() }}
    <div>
      <h5>予定名</h5>
      <input type="text" name="schedule_name">
    </div>
    <div>
      <h5>メモ</h5>
      <textarea name="memo"></textarea>
    </div>
    <div>
      <h5>候補日程 (改行して複数入力してください)</h5>
      <textarea name="candidates"></textarea>
    </div>
    <button type="submit">予定をつくる</button>
  </form>
@endsection

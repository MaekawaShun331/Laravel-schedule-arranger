@extends('layouts.app')

@section('content')
<div class="jumbotron my-3">
  <h1 class="display-4">{{ config('app.name', '予定調整くん') }}</h1>
  <p class="lead">{{ config('app.name', '予定調整くん') }}は、認証が出来る、予定を作って出欠が取れるサービスです</p>
</div>
    @if (Auth::check())
      <div>
        <a class="btn btn-info" href="/schedules/create">予定を作る</a>
      @if (count($schedules) > 0)
        <h3 class="my-3">あなたの作った予定一覧</h3>
        <table class="table">
          <tr>
            <th>予定名</th>
            <th>更新日時</th>
          </tr>
          @foreach ($schedules as $schedule)
            <tr>
              <td>
                <a href="/schedules/{{ $schedule->id }}">{{ $schedule->schedule_name }}</a>
              </td>
              <td> {{ $schedule->updated_at }}</td>
            </tr>
          @endforeach
        </table>
      @endif
    @endif
@endsection

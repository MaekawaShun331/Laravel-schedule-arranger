@extends('layouts.app')

@section('content')
<h1>{{ $title }}</h1>
<p>Welcome to {{ $title }}</p>
  <div>
    @empty (!$user)
      <div>
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
            {{ $user->name }}をログアウト
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
      </div>

      <div>
        <a href="/schedules/create">予定を作る</a>
      @if (count($schedules) > 0)
        <h3>あなたの作った予定一覧</h3>
        <table>
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
    @else
      <div>
        <a href="{{ route('login') }}">ログイン</a>
      </div>
    @endempty
  </div>
@endsection

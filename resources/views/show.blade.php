@extends('layouts.app')

@section('content')
  <h4>{{ $schedule->schedule_name }}</h4>
  <p style="white-space:pre;">{{ $schedule->memo }}</p>
  <p>作成者: {{ $schedule->user->name}}</p>
  <h3>出欠表</h3>
  <table>
    <tr>
      <th>予定</th>
      @foreach ($users as $user)
        <th>{{ $user->name}}</th>
      @endforeach
    </tr>
    @foreach ($candidates as $candidate)
      <tr>
        <th>{{ $candidate->candidate_name }}</th>
        @foreach ($users as $user)
          <td>
            <button>欠席</button>
          </td>
        @endforeach
      </tr>
    @endforeach
  </table>
@endsection

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
        <th>{{ $user['username']}}</th>
      @endforeach
    </tr>
    @foreach ($candidates as $candidate)
      <tr>
        <th>{{ $candidate->candidate_name }}</th>
        @foreach ($users as $user)
          @php
            $map = $availability_map_map[$user['user_id']];
            $availability = $map[$candidate->id];
            $availability_labels = ['欠', '？', '出'];
          @endphp
          <td>
            @if ($user['is_self'])
              <button>{{ $availability_labels[$availability] }}</button>
            @else
              <p>{{ $availability_labels[$availability] }}</p>
            @endif
          </td>
        @endforeach
      </tr>
    @endforeach
  </table>
@endsection

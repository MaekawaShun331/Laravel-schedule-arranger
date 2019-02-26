@extends('layouts.app')

@section('content')
  <h4 id="schedule_name" data-id="{{ $schedule->id }}">{{ $schedule->schedule_name }}</h4>
  <p style="white-space:pre;">{{ $schedule->memo }}</p>
  <p>作成者: {{ $schedule->user->name}}</p>
  @if ($schedule->user_id === Auth::user()->id)
    <div>
      <a href="/schedules/{{ $schedule->id }}/edit"> この予定を編集する</a>
    </div>
  @endif
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
              <button class="availability_change" data-candidate="{{ $candidate->id }}" data-availability="{{ $availability }}">
                {{ $availability_labels[$availability] }}
              </button>
            @else
              <p>{{ $availability_labels[$availability] }}</p>
            @endif
          </td>
        @endforeach
      </tr>
    @endforeach
    <tr>
      <th>コメント</th>
      @foreach ($users as $user)
        <td>
          @php
            $comment = array_key_exists($user['user_id'], $comment_map) ? $comment_map[$user['user_id']] : '';
          @endphp
          @if ($user['is_self'])
            <p id="comment_self">{{ $comment }}</p>
            <button id="comment_edit">編集</button>
          @else
            <p>{{ $comment}}</p>
          @endif
        </td>
      @endforeach
    </tr>
  </table>
@endsection

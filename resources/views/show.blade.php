@extends('layouts.app')

@section('content')
  <div class="card my-3">
    <div class="card-header">
      <h4 id="schedule_name" data-id="{{ $schedule->id }}">{{ $schedule->schedule_name }}</h4>
    </div>
    <div class="card-body">
      <p style="white-space:pre;">{{ $schedule->memo }}</p>
    </div>
    <div class="card-footer">
      <p>作成者: {{ $schedule->user->name}}</p>
    </div>
  </div>
  @if ($schedule->user_id === Auth::user()->id)
    <div>
      <a class="btn btn-info" href="/schedules/{{ $schedule->id }}/edit"> この予定を編集する</a>
    </div>
  @endif
  <h3 class="my-3">出欠表</h3>
  <table class="table table-bordered">
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
            $buttonStyles = ['btn-danger', 'btn-secondary', 'btn-success'];
          @endphp
          <td>
            @if ($user['is_self'])
              <button class="availability_change availability-toggle-button btn-lg {{ $buttonStyles[$availability] }}"
                      data-candidate="{{ $candidate->id }}" data-availability="{{ $availability }}">
                {{ $availability_labels[$availability] }}
              </button>
            @else
              <h3>{{ $availability_labels[$availability] }}</h3>
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
            <p id="comment_self">
              <small id="comment_self">{{ $comment }}</small>
            </p>
            <button id="comment_edit" class="btn-xs btn-info">編集</button>
          @else
            <p><small>{{ $comment}}</small></p>
          @endif
        </td>
      @endforeach
    </tr>
  </table>
@endsection

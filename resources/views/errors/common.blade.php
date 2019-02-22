@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-body">
          @php
            $status_code = $exception->getStatusCode();
            $message = $exception->getMessage();

            if (! $message) {
                switch ($status_code) {
                    case 400:
                        $message = '不正なリクエストです！';
                        break;
                    case 401:
                        $message = '認証に失敗しました';
                        break;
                    case 403:
                        $message = 'アクセス権がありません';
                        break;
                    case 404:
                        $message = 'お探しのページは見つかりませんでした';
                        break;
                    case 405:
                        //postのみ許可してるURLにブラウザからアクセスした場合、そのまま405を出すのは不親切なので表示だけ404に書き換える
                        $status_code = '404';
                        $message = 'お探しのページは見つかりませんでした';
                        break;
                    case 408:
                        $message = 'タイムアウトです';
                        break;
                    case 414:
                        $message = 'リクエストURIが長すぎます';
                        break;
                    case 419:
                        //CSRFエラー
                        $message = '不正なリクエストです！';
                        break;
                    case 500:
                        $message = 'アクセスしようとしたページは表示できませんでした';
                        break;
                    case 503:
                        $message = '現在サービス利用不可です';
                        break;
                    default:
                        $message = 'エラーで表示できませんでした';
                        break;
                }
            }
          @endphp
          <h1>{{ $status_code }}</h1>
          <h2>{{ $message }}</h2>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

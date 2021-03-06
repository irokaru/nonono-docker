@inject('ogp', 'App\Http\Controllers\OgpController')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta property="og:site_name" content="ののの茶屋"/>
  <meta property="og:url" content="{{ config('app.url')}}"/>
  <meta property="og:type" content="website"/>
  <meta property="og:title" content="{{ $ogp::getTitle($_SERVER['REQUEST_URI']) }}"/>
  <meta property="og:description" content="{{ $ogp::getDescription($_SERVER['REQUEST_URI']) }}"/>
  <meta property="og:image" content="{{ config('app.url') . $ogp::getThumbnail($_SERVER['REQUEST_URI']) }}"/>
  <meta  name="twitter:card" content="{{ $ogp::getCardTypeForTwitter($_SERVER['REQUEST_URI']) }}"/>
  <meta name="twitter:site" content="@irokaru"/>
  <meta name="twitter:creator" content="@irokaru"/>
  <meta charset="utf-8">
  <title>ののの茶屋</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

<div id="app"></div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

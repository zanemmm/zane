<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ url('css/normalize.css') }}">
    <style>
        html {
            height: 100%;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            color: white;
            text-align: center;
            background-color: #393E46;
        }
        h1 {
            font-size: 56px;
        }
        p {
            font-size: 24px;
        }
        a {
            text-decoration: none;
            font-size: 18px;
            color: #00ADB5;
        }
    </style>
    <title>404 错误! - {{ config('site.name') }}</title>
</head>
<body>
@component('components.error', ['status' => '404 错误!', 'message' => '找不到相关页面!'])@endcomponent
</body>
</html>
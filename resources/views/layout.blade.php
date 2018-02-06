<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="{{ $keywords ?? config('site.keywords') }}">
    <meta name="description" content="{{ $description ??  config('site.description') }}">
    <link rel="stylesheet" href="{{ url('css/main.css') }}"  type="text/css">
    <link rel="stylesheet" href="{{ url('css/highlight.css') }}"  type="text/css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" type="text/css">
    <title>{{ $title ?? config('site.title') }}</title>
</head>
<body id="body">
<div id="view">
    <header id="header">
        <div id="banner">
            <div id="name">
                <h1><a href="{{ url('/') }}">{{ config('site.name', "Zane's Blog") }}</a></h1>
                <h2>{{ config('site.intro', 'just for fun!') }}</h2>
            </div>
            <div id="avatar">
                <p>恶意</p>
                <img src="{{ url('/img/logo.jpg') }}" alt="logo">
                <p>卖萌</p>
            </div>
        </div>
        <nav id="navbar">
            @component('components.navbar', compact('categories'))@endcomponent
        </nav>
    </header>
    <div id="breadcrumb">
        @component('components.breadcrumb')@endcomponent
    </div>
    <main id="main">
        @yield('main')
    </main>
    <footer id="footer">
        @component('components.footer')@endcomponent
    </footer>
    <div class="toTop">&uarr;</div>
</div>
</body>
<script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.12.0/build/highlight.min.js"></script>
<script src="{{ url('js/main.js') }}"></script>
</html>
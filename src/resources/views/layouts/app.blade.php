<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/base.css') }}">
    @yield('css')
</head>

<body>
    @yield('header')

    @yield('main')
</body>

</html>
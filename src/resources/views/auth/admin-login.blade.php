@extends('layouts.app')

@section('title', 'ログイン画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/admin-login.css') }}">
@endsection

@section('header')
<header class="header">
    <div class="header__container">
        <a class="header__logo" href="#">
            <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
        </a>
    </div>
</header>
@endsection

@section('main')
<main>
    <div class="admin-login">
        <h1 class="admin-login__title">管理者ログイン</h1>
        <form class="admin-login__form" action="/admin/attendance/list" method="GET">
            <div class="admin-login__form-group">
                <label class="admin-login__label" for="email">メールアドレス</label>
                <input class="admin-login__input" type="text" id="email" name="email">
            </div>
            <div class="admin-login__form-group">
                <label class="admin-login__label" for="password">パスワード</label>
                <input class="admin-login__input" type="password" id="password" name="password">
            </div>
            <button class="admin-login__button" type="submit">管理者ログインする</button>
        </form>
    </div>
</main>
@endsection
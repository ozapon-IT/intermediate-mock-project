@extends('layouts.app')

@section('title', 'ログイン画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('header')
<x-header type="default" />
@endsection

@section('main')
<main>
    <div class="login">
        <h1 class="login__title">ログイン</h1>

        <form class="login__form" action="{{ route('login') }}" method="POST">
            @csrf

            <x-auth-form-group
                auth="login"
                label="メールアドレス"
                type="text"
                id="email"
                name="email"
                :value="old('email')"
            />

            <x-auth-form-group
                auth="login"
                label="パスワード"
                type="password"
                id="password"
                name="password"
            />

            <button class="login__button" type="submit">ログインする</button>
        </form>

        <div class="login__register-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </div>
</main>
@endsection
@extends('layouts.app')

@section('title', '会員登録画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('header')
<x-header type="default" />
@endsection

@section('main')
<main>
    <div class="register">
        <h1 class="register__title">会員登録</h1>

        <form class="register__form" action="{{ route('register') }}" method="POST">
            @csrf

            <x-auth-form-group
                auth="register"
                label="名前"
                type="text"
                id="name"
                name="name"
                :value="old('name')"
            />

            <x-auth-form-group
                auth="register"
                label="メールアドレス"
                type="text"
                id="email"
                name="email"
                :value="old('email')"
            />

            <x-auth-form-group
                auth="register"
                label="パスワード"
                type="password"
                id="password"
                name="password"
            />

            <x-auth-form-group
                auth="register"
                label="パスワード確認"
                type="password"
                id="password_confirmation"
                name="password_confirmation"
            />

            <button class="register__button" type="submit">登録する</button>
        </form>

        <div class="register__login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
</main>
@endsection
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

            <div class="login__form-group">
                <label class="login__label" for="email">メールアドレス</label>

                <input class="login__input" type="text" id="email" name="email" value="{{ old('email') }}">

                <x-validation-error field="email" />
            </div>

            <div class="login__form-group">
                <label class="login__label" for="password">パスワード</label>

                <input class="login__input" type="password" id="password" name="password">

                <x-validation-error field="password" />
            </div>

            <button class="login__button" type="submit">ログインする</button>
        </form>

        <div class="login__register-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </div>
</main>
@endsection
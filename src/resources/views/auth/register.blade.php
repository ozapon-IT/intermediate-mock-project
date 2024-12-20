@extends('layouts.app')

@section('title', '会員登録画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
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
    <div class="register">
        <h1 class="register__title">会員登録</h1>

        <form class="register__form" action="{{ route('register') }}" method="POST">
            @csrf

            <div class="register__form-group">
                <label class="register__label" for="name">名前</label>

                <input class="register__input" type="text" id="name" name="name" value="{{ old('name') }}">

                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="register__form-group">
                <label class="register__label" for="email">メールアドレス</label>

                <input class="register__input" type="text" id="email" name="email" value="{{ old('email') }}">

                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="register__form-group">
                <label class="register__label" for="password">パスワード</label>

                <input class="register__input" type="password" id="password" name="password"">

                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="register__form-group">
                <label class="register__label" for="password_confirmation">パスワード確認</label>

                <input class="register__input" type="password" id="password_confirmation" name="password_confirmation">

                @error('password_confirmation')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button class="register__button" type="submit">登録する</button>
        </form>

        <div class="register__login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
</main>
@endsection
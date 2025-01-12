@extends('layouts.app')

@section('title', 'ログイン画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/admin-login.css') }}">
@endsection

@section('header')
<x-header type="default" />
@endsection

@section('main')
<main>
    <div class="login">
        <h1 class="login__title">管理者ログイン</h1>

        <form class="login__form" action="{{ route('admin-login.login') }}" method="POST">
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

            <button class="login__button" type="submit">管理者ログインする</button>
        </form>
    </div>
</main>
@endsection
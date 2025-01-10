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
    <div class="admin-login">
        <h1 class="admin-login__title">管理者ログイン</h1>

        <form class="admin-login__form" action="{{ route('admin-login.login') }}" method="POST">
            @csrf

            <div class="admin-login__form-group">
                <label class="admin-login__label" for="email">メールアドレス</label>

                <input class="admin-login__input" type="text" id="email" name="email" value="{{ old('email') }}">

                <x-validation-error field="email" />
            </div>

            <div class="admin-login__form-group">
                <label class="admin-login__label" for="password">パスワード</label>

                <input class="admin-login__input" type="password" id="password" name="password">

                <x-validation-error field="password" />
            </div>

            <button class="admin-login__button" type="submit">管理者ログインする</button>
        </form>
    </div>
</main>
@endsection
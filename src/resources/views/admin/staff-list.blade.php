@extends('layouts.app')

@section('title', 'スタッフ一覧画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-list.css') }}">
@endsection

@section('header')
<header class="header">
    <div class="header__container">
        <a class="header__logo" href="#">
            <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
        </a>
        <nav class="header__nav">
            <a class="header__link" href="{{ route('admin.attendance-list.show') }}">勤怠一覧</a>

            <a class="header__link" href="{{ route('admin.staff-list.show') }}">スタッフ一覧</a>

            <a class="header__link" href="/admin/stamp_correction_request/list">申請一覧</a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf

                <button class="header__button" type="submit">ログアウト</button>
            </form>
        </nav>
    </div>
</header>
@endsection

@section('main')
<main>
    <div class="staff-list">
        <h1 class="staff-list__title">スタッフ一覧</h1>

        <table class="staff-list__records">
            <tr class="staff-list__item">
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>

            @foreach ($users as $user)
                <tr class="staff-list__item">
                    <td>{{ $user->name }}</td>

                    <td>{{ $user->email }}</td>

                    <td><a href="{{ route('admin.staff-attendance-list.show', $user->id) }}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</main>
@endsection
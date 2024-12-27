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
            <a class="header__link" href="{{ route('admin.attendance-list.show') }}" class="header__link">勤怠一覧</a>

            <a class="header__link" href="/admin/staff/list" class="header__link">スタッフ一覧</a>

            <a class="header__link" href="/admin/stamp_correction_request/list" class="header__link">申請一覧</a>

            <form action="{{ route('admin.logout') }}" method="GET">
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
            <tr class="staff-list__item">
                <td>西 怜奈</td>
                <td>reina.n@coachtech.com</td>
                <td><a href="/admin/attendance/staff/{id}">詳細</a></td>
            </tr>
            <tr class="staff-list__item">
                <td>山田 太郎</td>
                <td>taro.y@coachtech.com</td>
                <td><a href="#">詳細</a></td>
            </tr>
            <tr class="staff-list__item">
                <td>増田 一世</td>
                <td>issei.m@coachtech.com</td>
                <td><a href="#">詳細</a></td>
            </tr>
            <tr class="staff-list__item">
                <td>山本 敬吉</td>
                <td>keikichi.y@coachtech.com</td>
                <td><a href="#">詳細</a></td>
            </tr>
            <tr class="staff-list__item">
                <td>秋田 朋美</td>
                <td>tomomi.a@coachtech.com</td>
                <td><a href="#">詳細</a></td>
            </tr>
            <tr class="staff-list__item">
                <td>中西 敦夫</td>
                <td>norio.n@coachtech.com</td>
                <td><a href="#">詳細</a></td>
            </tr>
        </table>
    </div>
</main>
@endsection
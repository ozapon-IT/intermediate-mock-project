@extends('layouts.app')

@section('title', '勤怠詳細画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-detail.css') }}">
@endsection

@section('header')
<header class="header">
    <div class="header__container">
        <a class="header__logo" href="#">
            <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
        </a>
        <nav class="header__nav">
            <a class="header__link" href="/admin/attendance/list" class="header__link">勤怠一覧</a>
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
    <div class="attendance-detail">
        <h1 class="attendance-detail__title">勤怠詳細</h1>
        <table class="attendance-detail__records">
            <tr class="attendance-detail__item attendance-detail__name">
                <th>名前</th>
                <th>西 怜奈</th>
            </tr>
            <tr class="attendance-detail__item attendance-detail__date">
                <td>日付</td>
                <td>
                    <input type="text" value="2024年">
                    <input type="text" value="12月1日">
                </td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__working-time">
                <td>出勤・退勤</td>
                <td>
                    <input type="text" value="09:00">
                    <span>~</span>
                    <input type="text" value="18:00">
                </td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__break">
                <td>休憩</td>
                <td>
                    <input type="text" value="12:00">
                    <span>~</span>
                    <input type="text" value="13:00">
                </td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__break">
                <td>休憩2</td>
                <td>
                    <input type="text">
                    <span>~</span>
                    <input type="text">
                </td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__reason">
                <td>備考</td>
                <td>
                    <textarea name="" id=""></textarea>
                </td>
            </tr>
        </table>
        <div class="attendance-detail__correction">
            <form action="#">
                <button class="attendance-detail__button" type="submit">修正</button>
            </form>
        </div>
    </div>
</main>
@endsection
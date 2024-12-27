@extends('layouts.app')

@section('title', '勤怠一覧画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-list.css') }}">
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
    <div class="attendance-list">
        <h1 class="attendance-list__title">2024年12月1日の勤怠</h1>
        <div class="attendance-list__daily">
            <span class="attendance-list__previous-day">← 前日</span>
            <p class="attendance-list__calendar">2024/12/01</p>
            <span class="attendance-list__next-day">翌日 →</span>
        </div>
        <table class="attendance-list__records">
            <tr class="attendance-list__item">
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            <tr class="attendance-list__item">
                <td>西 怜奈</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td><a href="/admin/attendance/{id}">詳細</a></td>
            </tr>
            <tr class="attendance-list__item">
                <td>山田 太郎</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td><a href="#">詳細</a></td>
            </tr>
            <tr class="attendance-list__item">
                <td>増田 一世</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>山本 敬吉</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>秋田 朋美</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>中西 敦夫</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
        </table>
    </div>
</main>
@endsection
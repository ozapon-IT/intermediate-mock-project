@extends('layouts.app')

@section('title', '勤怠一覧画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('header')
<header class="header">
    <div class="header__container">
        <a class="header__logo" href="#">
            <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
        </a>
        <nav class="header__nav">
            <a class="header__link" href="/attendance" class="header__link">勤怠</a>
            <a class="header__link" href="/attendance/list" class="header__link">勤怠一覧</a>
            <a class="header__link" href="/stamp_correction_request/list" class="header__link">申請</a>
            <form action="/login" method="GET">
                <button class="header__button" type="submit">ログアウト</button>
            </form>
        </nav>
    </div>
</header>
@endsection

@section('main')
<main>
    <div class="attendance-list">
        <h1 class="attendance-list__title">勤怠一覧</h1>
        <div class="attendance-list__monthly">
            <span class="attendance-list__previous-month">← 前月</span>
            <p class="attendance-list__calendar">2004年12月</p>
            <span class="attendance-list__next-month">翌月 →</span>
        </div>
        <table class="attendance-list__records">
            <tr class="attendance-list__item">
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            <tr class="attendance-list__item">
                <td>12/01(日)</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td><a href="/attendance/{id}">詳細</a></td>
            </tr>
            <tr class="attendance-list__item">
                <td>12/02(月)</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>12/03(火)</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>12/04(水)</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>12/05(木)</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>12/06(金)</td>
                <td>09:00</td>
                <td>18:00</td>
                <td>1:00</td>
                <td>8:00</td>
                <td>詳細</td>
            </tr>
            <tr class="attendance-list__item">
                <td>12/07(土)</td>
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
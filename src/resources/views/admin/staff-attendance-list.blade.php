@extends('layouts.app')

@section('title', 'スタッフ別勤怠一覧画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-attendance-list.css') }}">
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
        <h1 class="attendance-list__title">西怜奈さんの勤怠</h1>
        <div class="attendance-list__monthly">
            <span class="attendance-list__previous-month">← 前月</span>
            <p class="attendance-list__calendar">2004/12</p>
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
                <td><a href="/admin/attendance/{id}">詳細</a></td>
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
        <div class="attendance-list__export">
            <button class="attendance-list__button">CSV出力</button>
        </div>
    </div>
</main>
@endsection
@extends('layouts.app')

@section('title', '修正申請承認画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/request-approval.css') }}">
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
                    <p>2024年</p>
                    <p>12月1日</p>
                </td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__working-time">
                <td>出勤・退勤</td>
                <td>
                    <p>09:00</p>
                    <span>~</span>
                    <p>18:00</p>
                </td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__break">
                <td>休憩</td>
                <td>
                    <p>12:00</p>
                    <span>~</span>
                    <p>13:00</p>
                </td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__break">
                <td>休憩2</td>
                <td></td>
            </tr>
            <tr class="attendance-detail__item attendance-detail__reason">
                <td>備考</td>
                <td>
                    <p>電車遅延のため</p>
                </td>
            </tr>
        </table>
        <div class="attendance-detail__approval">
            <form action="#">
                <button class="attendance-detail__button attendance-detail__button--approve" type="submit">承認</button>
                <button class="attendance-detail__button attendance-detail__button--approved" type="submit">承認済み</button>
            </form>
        </div>
    </div>
</main>
@endsection
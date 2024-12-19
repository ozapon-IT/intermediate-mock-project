@extends('layouts.app')

@section('title', '申請一覧画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/request-list.css') }}">
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
            <form action="/admin/login" method="GET">
                <button class="header__button" type="submit">ログアウト</button>
            </form>
        </nav>
    </div>
</header>
@endsection

@section('main')
<main>
    <div class="request-list">
        <h1 class="request-list__title">申請一覧</h1>
        <div class="request-list__tabs">
            <p class="request-list__tab request-list__tab--waiting">承認待ち</p>
            <p class="request-list__tab request-list__tab--done">承認済み</p>
        </div>
        <table class="request-list__records">
            <tr class="request-list__item">
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
            <tr class="request-list__item">
                <td>承認待ち</td>
                <td>西怜奈</td>
                <td>2024/12/01</td>
                <td>遅延のため</td>
                <td>2024/12/02</td>
                <td><a href="/stamp_correction_request/approval/{attendance_correct_request}">詳細</a></td>
            </tr>
            <tr class="request-list__item">
                <td>承認待ち</td>
                <td>山田 太郎</td>
                <td>2024/12/01</td>
                <td>遅延のため</td>
                <td>2024/12/02</td>
                <td><a href="#">詳細</a></td>
            </tr>
            <tr class="request-list__item">
                <td>承認待ち</td>
                <td>山田 花子</td>
                <td>2024/12/01</td>
                <td>遅延のため</td>
                <td>2024/12/02</td>
                <td><a href="#">詳細</a></td>
            </tr>
        </table>
    </div>
</main>
@endsection
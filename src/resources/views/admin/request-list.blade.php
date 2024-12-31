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
            <a class="header__link" href="{{ route('admin.attendance-list.show') }}">勤怠一覧</a>

            <a class="header__link" href="{{ route('admin.staff-list.show') }}">スタッフ一覧</a>

            <a class="header__link" href="{{ route('admin.request-list.show') }}">申請一覧</a>

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
    <div class="request-list">
        <h1 class="request-list__title">申請一覧</h1>

        <div class="request-list__tabs">
            <a class="request-list__tab {{ $status === '承認待ち' ? 'request-list__tab--active' : ''}}" href="{{ route('admin.request-list.show', ['status' => '承認待ち']) }}">
                承認待ち
            </a>

            <a class="request-list__tab {{ $status === '承認済み' ? 'request-list__tab--active' : ''}}" href="{{ route('admin.request-list.show', ['status' => '承認済み']) }}">
                承認済み
            </a>
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

            @foreach ($attendanceCorrections as $attendanceCorrection)
                <tr class="request-list__item">
                    <td>{{ $attendanceCorrection->status }}</td>
                    <td>{{ $attendanceCorrection->user->name }}</td>
                    <td>{{ $attendanceCorrection->formatted_old_date }}</td>
                    <td>{{ $attendanceCorrection->reason }}</td>
                    <td>{{ $attendanceCorrection->formatted_requested_date }}</td>
                    <td><a href="{{-- route('admin.approve-request.show') --}}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</main>
@endsection
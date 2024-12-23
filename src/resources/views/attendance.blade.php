@extends('layouts.app')

@section('title', '勤怠登録画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
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

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button class="header__button" type="submit">ログアウト</button>
            </form>
        </nav>
    </div>
</header>
@endsection

@section('main')
<main>
    <div class="attendance">
        <span class="attendance__status">{{ $status }}</span>

        <p class="attendance__date">{{ $formattedDate }}</p>

        <p class="attendance__time">{{ $formattedTime }}</p>

        @if ($status === '勤務外')
        <form action="{{ route('attendance.clock_in') }}" method="POST">
            @csrf

            <button class="attendance__button attendance__working" type="submit">出勤</button>
        </form>
        @endif

        @if ($status === '出勤中')
        <div class="attendance__buttons">
            <form action="{{ route('attendance.clock_out') }}" method="POST">
                @csrf

                <button class="attendance__button attendance__leaving" type="submit">退勤</button>
            </form>

            <form action="{{ route('attendance.break_in') }}" method="POST">
                @csrf

                <input type="hidden" name="attendance_record_id" value="{{ $attendanceRecord->id }}">

                <button class="attendance__button attendance__break--begins" type="submit">休憩入</button>
            </form>
        </div>
        @endif

        @if ($status === '休憩中')
        <form action="{{ route('attendance.break_out') }}" method="POST">
            @csrf

            <input type="hidden" name="attendance_record_id" value="{{ $attendanceRecord->id }}">

            <button class="attendance__button attendance__break--ends" type="submit">休憩戻</button>
        </form>
        @endif

        @if ($status === '退勤済')
        <p class="attendance__message">お疲れ様でした。</p>
        @endif
    </div>
</main>
@endsection
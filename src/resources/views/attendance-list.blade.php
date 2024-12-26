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
            <a class="header__link" href="{{ route('attendance.show') }}" class="header__link">勤怠</a>

            <a class="header__link" href="{{ route('attendance-list.show') }}" class="header__link">勤怠一覧</a>

            <a class="header__link" href="{{ route('request-list.show') }}" class="header__link">申請</a>

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
    <div class="attendance-list">
        <h1 class="attendance-list__title">勤怠一覧</h1>

        <div class="attendance-list__monthly">
            <a href="{{ route('attendance-list.show', ['month' => $previousMonth]) }}" class="attendance-list__previous-month"><i class="bi bi-arrow-left-short"></i> 前月</a>

            <p class="attendance-list__calendar"><i class="bi bi-calendar3"></i> {{ $currentMonthFormatted }}</p>

            <a href="{{ route('attendance-list.show', ['month' => $nextMonth]) }}" class="attendance-list__next-month">翌月 <i class="bi bi-arrow-right-short"></i></a>
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

            @foreach ($attendanceRecords as $record)
            <tr class="attendance-list__item">
                <td>{{ $record->formatted_date }}</td>

                <td>{{ $record->formatted_clock_in }}</td>

                <td>{{ $record->formatted_clock_out }}</td>

                <td>{{ $record->formatted_break_hours }}</td>

                <td>{{ $record->formatted_work_hours }}</td>

                <td><a href="{{ route('attendance-detail.show', $record->id) }}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</main>
@endsection
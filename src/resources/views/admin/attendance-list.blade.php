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
            <a class="header__link" href="{{ route('admin.attendance-list.show') }}">勤怠一覧</a>

            <a class="header__link" href="{{ route('admin.staff-list.show') }}">スタッフ一覧</a>

            <a class="header__link" href="/admin/stamp_correction_request/list">申請一覧</a>

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
        <h1 class="attendance-list__title">{{ $currentDay }}の勤怠</h1>

        <div class="attendance-list__daily">
            <a class="attendance-list__previous-day" href="{{ route('admin.attendance-list.show', ['day' => $previousDay]) }}"><i class="bi bi-arrow-left-short"></i> 前日</a>

            <p class="attendance-list__calendar">{{ $currentDayFormatted }}</p>

            <a class="attendance-list__next-day" href="{{ route('admin.attendance-list.show', ['day' => $nextDay]) }}">翌日 <i class="bi bi-arrow-right-short"></i></a>
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

            @foreach ($attendanceRecords as $record)
                <tr class="attendance-list__item">
                    <td>{{ $record->user->name }}</td>

                    <td>{{ $record->formatted_clock_in }}</td>

                    <td>{{ $record->formatted_clock_out }}</td>

                    <td>{{ $record->formatted_break_hours }}</td>

                    <td>{{ $record->formatted_work_hours }}</td>

                    <td><a href="{{ route('admin.attendance-detail.show', $record->id) }}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</main>
@endsection
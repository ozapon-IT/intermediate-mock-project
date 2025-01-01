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
    <div class="attendance-list">
        <h1 class="attendance-list__title">{{ $user->name }}さんの勤怠</h1>

        <div class="attendance-list__monthly">
            <a class="attendance-list__previous-month" href="{{ route('admin.staff-attendance-list.show', ['month' => $previousMonth, 'id' => $user->id]) }}"><i class="bi bi-arrow-left-short"></i> 前月</a>

            <p class="attendance-list__calendar"><i class="bi bi-calendar3"></i> {{ $currentMonthFormatted }}</p>

            <a class="attendance-list__next-month" href="{{ route('admin.staff-attendance-list.show', ['month' => $nextMonth, 'id' => $user->id]) }}">翌月 <i class="bi bi-arrow-right-short"></i></a>
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

                <td><a href="{{ route('admin.attendance-detail.show', $record->id) }}">詳細</a></td>
            </tr>
            @endforeach
        </table>

        <div class="attendance-list__export">
            <a class="attendance-list__export-button" href="{{ route('admin.staff-attendance-list.export', ['id' => $user->id, 'month' => $currentMonth]) }}">CSV出力</a>
        </div>
    </div>
</main>
@endsection
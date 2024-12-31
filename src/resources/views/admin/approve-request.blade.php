@extends('layouts.app')

@section('title', '修正申請承認画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approve-request.css') }}">
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
    <div class="attendance-detail">
        <h1 class="attendance-detail__title">勤怠詳細</h1>

        <form action="{{ route('admin.approve-request.approve', ['attendance_correct_request' => $attendanceCorrection->id]) }}" method="POST">
            @csrf

            <table class="attendance-detail__records">
                <tr class="attendance-detail__item attendance-detail__name">
                    <th>名前</th>

                    <th>{{ $attendanceCorrection->user->name }}</th>
                </tr>

                <tr class="attendance-detail__item attendance-detail__date">
                    <td>日付</td>

                    <td>
                        <p>{{ $attendanceCorrection->formatted_year }}</p>

                        <p>{{ $attendanceCorrection->formatted_month_day }}</p>

                        <input type="hidden" name="year" value="{{ $attendanceCorrection->formatted_year }}">
                        <input type="hidden" name="month_day" value="{{ $attendanceCorrection->formatted_month_day }}">
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__working-time">
                    <td>出勤・退勤</td>
                    <td>
                        <p>{{ $attendanceCorrection->formatted_new_clock_in }}</p>

                        <span>〜</span>

                        <p>{{ $attendanceCorrection->formatted_new_clock_out }}</p>

                        <input type="hidden" name="clock_in" value="{{ $attendanceCorrection->formatted_new_clock_in }}">
                        <input type="hidden" name="clock_out" value="{{ $attendanceCorrection->formatted_new_clock_out }}">
                    </td>
                </tr>

                @foreach ($attendanceBreaks as $index => $attendanceBreak)
                    <tr class="attendance-detail__item attendance-detail__break">
                        <td>{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</td>

                        <td>
                            <p>{{ $attendanceBreak->formatted_new_break_in }}</p>

                            <span>〜</span>

                            <p>{{ $attendanceBreak->formatted_new_break_out }}</p>

                            <input type="hidden" name="break_in[{{ $index }}]" value="{{ $attendanceBreak->formatted_new_break_in }}">
                            <input type="hidden" name="break_out[{{ $index }}]" value="{{ $attendanceBreak->formatted_new_break_out }}">
                        </td>
                    </tr>
                @endforeach

                <tr class="attendance-detail__item attendance-detail__reason">
                    <td>備考</td>

                    <td>
                        <p>{{ $attendanceCorrection->reason }}</p>

                        <input type="hidden" name="reason" value="{{ $attendanceCorrection->reason }}">
                    </td>
                </tr>
            </table>

            <div class="attendance-detail__approval">
                @if ($isWaitingApproval)
                    <button class="attendance-detail__button attendance-detail__button--approve" type="submit">承認</button>
                @else
                    <button class="attendance-detail__button attendance-detail__button--approved">承認済み</button>
                @endif
            </div>
        </form>
    </div>
</main>
@endsection
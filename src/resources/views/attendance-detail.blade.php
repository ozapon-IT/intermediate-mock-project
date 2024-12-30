@extends('layouts.app')

@section('title', '勤怠詳細画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('header')
<header class="header">
    <div class="header__container">
        <a class="header__logo" href="#">
            <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
        </a>
        <nav class="header__nav">
            <a class="header__link" href="{{ route('attendance.show') }}">勤怠</a>

            <a class="header__link" href="{{ route('attendance-list.show') }}">勤怠一覧</a>

            <a class="header__link" href="{{ route('request-list.show') }}">申請</a>

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
    <div class="attendance-detail">
        <h1 class="attendance-detail__title">勤怠詳細</h1>

        <form action="{{ route('attendance-detail.request_correction', $attendanceRecord->id) }}" method="POST">
            @csrf

            <table class="attendance-detail__records">
                <tr class="attendance-detail__item attendance-detail__name">
                    <th>名前</th>

                    <th>{{ Auth::user()->name }}</th>
                </tr>

                <tr class="attendance-detail__item attendance-detail__date">
                    <td>日付</td>

                    <td>
                        @if ($isWaitingApproval && $attendanceCorrection)
                            <p>{{ $attendanceCorrection->formatted_year }}</p>

                            <p>{{ $attendanceCorrection->formatted_month_day }}</p>
                        @else
                            <div>
                                <input type="text" name="year" value="{{ $attendanceRecord->formatted_year }}">

                                <input type="text" name="month_day" value="{{ $attendanceRecord->formatted_month_day }}">

                                @error('year')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror

                                @error('month_day')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__working-time">
                    <td>出勤・退勤</td>

                    <td>
                        @if ($isWaitingApproval && $attendanceCorrection)
                            <p>{{ $attendanceCorrection->formatted_new_clock_in }}</p>

                            <span>〜</span>

                            <p>{{ $attendanceCorrection->formatted_new_clock_out }}</p>
                        @else
                            <div>
                                <input type="text" name="clock_in" value="{{ $attendanceRecord->formatted_clock_in }}">

                                <span>〜</span>

                                <input type="text" name="clock_out" value="{{ $attendanceRecord->formatted_clock_out }}">

                                @error('clock_in')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror

                                @error('clock_out')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </td>
                </tr>

                @if ($isWaitingApproval && $breakCorrections)
                    @foreach ($breakCorrections as $index => $breakCorrection)
                        <tr class="attendance-detail__item attendance-detail__break">
                            <td>{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</td>

                            <td>
                                <p>{{ $breakCorrection->formatted_new_break_in }}</p>

                                <span>〜</span>

                                <p>{{ $breakCorrection->formatted_new_break_out }}</p>
                            </td>
                        </tr>
                    @endforeach
                @else
                    @foreach ($breaks as $index => $break)
                        <tr class="attendance-detail__item attendance-detail__break">
                            <td>{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</td>

                            <td>
                                <div>
                                    <input type="text" name="break_in[{{ $index }}]" value="{{ $break->formatted_break_in }}">

                                    <span>〜</span>

                                    <input type="text" name="break_out[{{ $index }}]" value="{{ $break->formatted_break_out }}">

                                    @error('break_in.'.$index)
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror

                                    @error('break_out.'.$index)
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif


                <tr class="attendance-detail__item attendance-detail__reason">
                    <td>備考</td>

                    <td>
                        @if ($isWaitingApproval && $attendanceCorrection)
                            <p>{{ $attendanceCorrection->reason }}</p>
                        @else
                            <div>
                                <textarea name="reason">{{ old('reason') }}</textarea>

                                @error('reason')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="attendance-detail__correction">
                @if ($isWaitingApproval)
                    <p>*承認待ちの為修正はできません。</p>
                @else
                    <button class="attendance-detail__button" type="submit">修正</button>
                @endif
            </div>
        </form>
    </div>
</main>
@endsection
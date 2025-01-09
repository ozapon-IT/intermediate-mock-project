@extends('layouts.app')

@section('title', '勤怠一覧画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('header')
<x-header type="user" />
@endsection

@section('main')
<main>
    <div class="attendance-list">
        <h1 class="attendance-list__title">勤怠一覧</h1>

        <x-navigation
            :previousLink="route('attendance-list.show', ['month' => $previousMonth])"
            :currentLabel="$currentMonthFormatted"
            :nextLink="route('attendance-list.show', ['month' => $nextMonth])"
            type="month"
        />

        <x-attendance-list-table
            :attendanceRecords="$attendanceRecords"
            :columns="['日付', '出勤', '退勤', '休憩', '合計', '詳細']"
            :fields="['formatted_date', 'formatted_clock_in', 'formatted_clock_out', 'formatted_break_hours', 'formatted_work_hours']"
            :detailRoute="fn($record) => route('attendance-detail.show', $record->id)"
        />
    </div>
</main>
@endsection
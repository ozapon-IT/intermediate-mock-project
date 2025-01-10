@extends('layouts.app')

@section('title', '勤怠一覧画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-list.css') }}">
@endsection

@section('header')
<x-header type="admin" />
@endsection

@section('main')
<main>
    <div class="attendance-list">
        <h1 class="attendance-list__title">{{ $currentDay }}の勤怠</h1>

        <x-navigation
            :previousLink="route('admin.attendance-list.show', ['day' => $previousDay])"
            :currentLabel="$currentDayFormatted"
            :nextLink="route('admin.attendance-list.show', ['day' => $nextDay])"
            type="day"
        />

        <x-attendance-list-table
            :attendanceRecords="$attendanceRecords"
            :columns="['名前', '出勤', '退勤', '休憩', '合計', '詳細']"
            :fields="[fn($record) => $record->user->name, 'formatted_clock_in', 'formatted_clock_out', 'formatted_break_hours', 'formatted_work_hours']"
            :detailRoute="fn($record) => route('admin.attendance-detail.show', $record->id)"
        />
    </div>
</main>
@endsection
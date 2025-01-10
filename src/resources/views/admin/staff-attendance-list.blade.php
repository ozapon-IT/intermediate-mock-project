@extends('layouts.app')

@section('title', 'スタッフ別勤怠一覧画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-attendance-list.css') }}">
@endsection

@section('header')
<x-header type="admin" />
@endsection

@section('main')
<main>
    <div class="attendance-list">
        <h1 class="attendance-list__title">{{ $user->name }}さんの勤怠</h1>

        <x-navigation
            :previousLink="route('admin.staff-attendance-list.show', ['month' => $previousMonth, 'id' => $user->id])"
            :currentLabel="$currentMonthFormatted"
            :nextLink="route('admin.staff-attendance-list.show', ['month' => $nextMonth, 'id' => $user->id])"
            type="month"
        />

        <x-attendance-list-table
            :attendanceRecords="$attendanceRecords"
            :columns="['日付', '出勤', '退勤', '休憩', '合計', '詳細']"
            :fields="['formatted_date', 'formatted_clock_in', 'formatted_clock_out', 'formatted_break_hours', 'formatted_work_hours']"
            :detailRoute="fn($record) => route('admin.attendance-detail.show', $record->id)"
        />

        <div class="attendance-list__export">
            <a class="attendance-list__export-button" href="{{ route('admin.staff-attendance-list.export', ['id' => $user->id, 'month' => $currentMonth]) }}">CSV出力</a>
        </div>
    </div>
</main>
@endsection
@extends('layouts.app')

@section('title', '申請一覧画面（一般ユーザー） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request-list.css') }}">
@endsection

@section('header')
<x-header type="user" />
@endsection

@section('main')
<main>
    <div class="request-list">
        <h1 class="request-list__title">申請一覧</h1>

        <x-tabs
            :status="$status"
            route-name="request-list.show"
            :tabs="['承認待ち' => '承認待ち', '承認済み' => '承認済み']"
        />

        <x-request-list-table
            :attendanceCorrections="$attendanceCorrections"
            :columns="['状態', '名前', '対象日時', '申請理由', '申請日時', '詳細']"
            :fields="['status', fn($attendanceCorrection) => $attendanceCorrection->user->name, 'formatted_old_date', 'reason', 'formatted_requested_date']"
            :detailRoute="fn($attendanceCorrection) => route('attendance-detail.show', $attendanceCorrection->attendance_record_id)"
        />
    </div>
</main>
@endsection
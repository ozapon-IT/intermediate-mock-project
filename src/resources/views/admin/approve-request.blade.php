@extends('layouts.app')

@section('title', '修正申請承認画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approve-request.css') }}">
@endsection

@section('header')
<x-header type="admin" />
@endsection

@section('main')
<main>
    <div class="attendance-detail">
        <h1 class="attendance-detail__title">勤怠詳細</h1>

        <form action="{{ route('admin.approve-request.approve', ['attendance_correct_request' => $attendanceCorrection->id]) }}" method="POST">
            @csrf

            <x-attendance-detail-table
                mode="approval"
                :attendanceCorrection="$attendanceCorrection"
                :breakCorrections="$breakCorrections"
            />

            <div class="attendance-detail__approval">
                <x-attendance-detail-button type="approval" :isWaitingApproval="$isWaitingApproval" />
            </div>
        </form>
    </div>
</main>
@endsection
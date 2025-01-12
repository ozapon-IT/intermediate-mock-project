@extends('layouts.app')

@section('title', '勤怠詳細画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-detail.css') }}">
@endsection

@section('header')
<x-header type="admin" />
@endsection

@section('main')
<main>
    <div class="attendance-detail">
        <h1 class="attendance-detail__title">勤怠詳細</h1>

        <form action="{{ route('admin.attendance-detail.correct', $attendanceRecord->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <x-attendance-detail-table
                mode="admin"
                :attendanceRecord="$attendanceRecord"
                :attendanceCorrection="$attendanceCorrection"
                :breaks="$breaks"
                :breakCorrections="$breakCorrections"
                :isWaitingApproval="$isWaitingApproval"
            />

            <div class="attendance-detail__correction">
                <x-attendance-detail-button type="correction" :isWaitingApproval="$isWaitingApproval" />
            </div>
        </form>
    </div>
</main>
@endsection
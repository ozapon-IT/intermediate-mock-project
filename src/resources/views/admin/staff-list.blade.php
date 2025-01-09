@extends('layouts.app')

@section('title', 'スタッフ一覧画面（管理者） - COACHTECH勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-list.css') }}">
@endsection

@section('header')
<x-header type="admin" />
@endsection

@section('main')
<main>
    <div class="staff-list">
        <h1 class="staff-list__title">スタッフ一覧</h1>

        <table class="staff-list__records">
            <tr class="staff-list__item">
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>

            @foreach ($users as $user)
                <tr class="staff-list__item">
                    <td>{{ $user->name }}</td>

                    <td>{{ $user->email }}</td>

                    <td><a href="{{ route('admin.staff-attendance-list.show', $user->id) }}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</main>
@endsection
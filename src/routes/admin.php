<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminAttendanceListController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\AttendanceCorrectionController;

Route::get('login', [AdminAuthController::class, 'show'])->name('admin-login.show');
Route::post('login', [AdminAuthController::class, 'login'])->name('admin-login.login');

Route::middleware(['admin.session', 'admin'])->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('attendance/list', [AdminAttendanceListController::class, 'show'])->name('admin.attendance-list.show');

    Route::get('attendance/{id}', [AttendanceDetailController::class, 'show'])->name('admin.attendance-detail.show');

    Route::post('attendance/request_correction/{id}', [AttendanceCorrectionController::class, 'requestCorrection'])->name('admin.attendance-detail.request_correction');
    Route::get('attendance/wait_approval/{id}', [AttendanceCorrectionController::class, 'waitApproval'])->name('admin.attendance-detail.wait_approval');

    Route::get('staff/list', function () {
        return view('admin.staff-list');
    });

    Route::get('attendance/staff/{id}', function () {
        return view('admin.staff-attendance-list');
    });

    Route::get('stamp_correction_request/list', function () {
        return view('admin.request-list');
    });

    Route::get('stamp_correction_request/approval/{attendance_correct_request}', function () {
        return view('admin.request-approval');
    });
});
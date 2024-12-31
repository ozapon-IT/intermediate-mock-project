<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminAttendanceListController;
use App\Http\Controllers\AdminAttendanceDetailController;
use App\Http\Controllers\AttendanceCorrectionController;
use App\Http\Controllers\AdminStaffListController;
use App\Http\Controllers\AdminStaffAttendanceListController;
use App\Http\Controllers\AdminRequestListController;

// 管理者用ルート
Route::get('login', [AdminAuthController::class, 'show'])->name('admin-login.show');
Route::post('login', [AdminAuthController::class, 'login'])->name('admin-login.login');

Route::middleware(['admin.session', 'admin'])->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('attendance/list', [AdminAttendanceListController::class, 'show'])->name('admin.attendance-list.show');

    Route::get('attendance/{id}', [AdminAttendanceDetailController::class, 'show'])->name('admin.attendance-detail.show');

    Route::post('attendance/request_correction/{id}', [AttendanceCorrectionController::class, 'requestCorrection'])->name('admin.attendance-detail.request_correction');
    Route::get('attendance/wait_approval/{id}', [AttendanceCorrectionController::class, 'waitApproval'])->name('admin.attendance-detail.wait_approval');

    Route::get('staff/list', [AdminStaffListController::class, 'show'])->name('admin.staff-list.show');

    Route::get('attendance/staff/{id}', [AdminStaffAttendanceListController::class, 'show'])->name('admin.staff-attendance-list.show');

    Route::get('stamp_correction_request/list', [AdminRequestListController::class, 'show'])->name('admin.request-list.show');

    Route::get('stamp_correction_request/approve/{attendance_correct_request}', [AdminApproveRequestController::class, 'show'])->name('admin.approve-request.show');
});
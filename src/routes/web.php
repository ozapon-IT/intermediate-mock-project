<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceBreakController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'show'])->name('attendance.show');

    Route::post('/attendance/clock_in', [AttendanceController::class, 'clockIn'])->name('attendance.clock_in');

    Route::post('/attendance/break_in', [AttendanceBreakController::class, 'breakIn'])->name('attendance.break_in');

    Route::post('/attendance/break_out', [AttendanceBreakController::class, 'breakOut'])->name('attendance.break_out');

    Route::post('/attendance/clock_out', [AttendanceController::class, 'clockOut'])->name('attendance.clock_out');

    Route::get('/attendance/list', function () {
        return view('attendance-list');
    });

    Route::get('/attendance/{id}', function () {
        return view('attendance-detail');
    });

    Route::get('/stamp_correction_request/list', function () {
        return view('request-list');
    });
});



Route::get('/admin/login', function () {
    return view('auth.admin-login');
});

Route::get('/admin/attendance/list', function () {
    return view('admin.attendance-list');
});

Route::get('/admin/attendance/{id}', function () {
    return view('admin.attendance-detail');
});

Route::get('/admin/staff/list', function () {
    return view('admin.staff-list');
});

Route::get('/admin/attendance/staff/{id}', function () {
    return view('admin.staff-attendance-list');
});

Route::get('/admin/stamp_correction_request/list', function () {
    return view('admin.request-list');
});

Route::get('/stamp_correction_request/approval/{attendance_correct_request}', function () {
    return view('admin.request-approval');
});
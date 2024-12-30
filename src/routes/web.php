<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceBreakController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\AttendanceCorrectionController;
use App\Http\Controllers\RequestListController;

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
    Route::post('/attendance/clock_out', [AttendanceController::class, 'clockOut'])->name('attendance.clock_out');

    Route::post('/attendance/break_in', [AttendanceBreakController::class, 'breakIn'])->name('attendance.break_in');
    Route::post('/attendance/break_out', [AttendanceBreakController::class, 'breakOut'])->name('attendance.break_out');


    Route::get('/attendance/list', [AttendanceListController::class, 'show'])->name('attendance-list.show');

    Route::get('/attendance/{id}', [AttendanceDetailController::class, 'show'])->name('attendance-detail.show');

    Route::post('/attendance/request_correction/{id}', [AttendanceCorrectionController::class, 'requestCorrection'])->name('attendance-detail.request_correction');
    Route::get('/attendance/wait_approval/{id}', [AttendanceCorrectionController::class, 'waitApproval'])->name('attendance-detail.wait_approval');

    Route::get('/stamp_correction_request/list', [RequestListController::class, 'show'])->name('request-list.show');
});
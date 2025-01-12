<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceBreakController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\AttendanceCorrectionController;
use App\Http\Controllers\RequestListController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

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
// 一般ユーザー用ルート
Route::get('/', function () {
    return redirect('/login');
});

// ログイン済みルート
Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/clock_in', [AttendanceController::class, 'clockIn'])->name('attendance.clock_in');
    Route::post('/attendance/clock_out', [AttendanceController::class, 'clockOut'])->name('attendance.clock_out');
    Route::post('/attendance/break_in', [AttendanceBreakController::class, 'breakIn'])->name('attendance.break_in');
    Route::post('/attendance/break_out', [AttendanceBreakController::class, 'breakOut'])->name('attendance.break_out');

    Route::get('/attendance/list', [AttendanceListController::class, 'show'])->name('attendance-list.show');

    Route::get('/attendance/{id}', [AttendanceDetailController::class, 'show'])->name('attendance-detail.show');
    Route::post('/attendance/{id}', [AttendanceCorrectionController::class, 'requestCorrection'])->name('attendance-detail.request_correction');

    Route::get('/stamp_correction_request/list', [RequestListController::class, 'show'])->name('request-list.show');
});

// デフォルトのメール認証ルートを上書き(カスタムミドルウェア'setUserFromId'を追加)
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'setUserFromId'])->name('verification.verify');
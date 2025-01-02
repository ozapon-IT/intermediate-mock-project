<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function show()
    {
        $now = Carbon::now('Asia/Tokyo');
        $formattedDate = $now->isoFormat('YYYY年MM月DD日(ddd)');
        $formattedTime = $now->format('H:i');
        $attendanceRecord = AttendanceRecord::where('user_id', Auth::id())->where('date', Carbon::today())->first();
        $status = $attendanceRecord->status ?? '勤務外';

        return view('attendance', compact('formattedDate', 'formattedTime', 'status', 'attendanceRecord'));
    }

    public function clockIn(Request $request)
    {
        AttendanceRecord::create([
            'user_id' => auth()->id(),
            'date' => Carbon::today(),
            'clock_in' => Carbon::now()->format('Y-m-d H:i:00'),
            'status' => '出勤中',
        ]);

        return redirect()->route('attendance.show');
    }

    public function clockOut(Request $request)
    {
        $attendance = AttendanceRecord::where('user_id', auth()->id())->whereNull('clock_out')->first();

        $attendance->update([
            'clock_out' => Carbon::now()->format('Y-m-d H:i:00'),
            'break_hours' => $attendance->calculateBreakHours(),
            'work_hours' => $attendance->calculateWorkHours(),
            'status' => '退勤済',
        ]);

        return redirect()->route('attendance.show');
    }
}

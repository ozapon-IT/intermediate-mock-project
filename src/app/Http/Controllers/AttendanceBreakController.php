<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceBreak;
use Carbon\Carbon;
use App\Models\AttendanceRecord;


class AttendanceBreakController extends Controller
{
    public function breakIn(Request $request)
    {
        AttendanceBreak::create([
            'attendance_record_id' => $request->attendance_record_id,
            'break_in' => Carbon::now()->format('Y-m-d H:i:00'),
        ]);

        AttendanceRecord::where('id', $request->attendance_record_id)
            ->update([
                'status' => '休憩中'
            ]);

        return redirect()->route('attendance.show');
    }

    public function breakOut(Request $request)
    {
        $break = AttendanceBreak::where('attendance_record_id', $request->attendance_record_id)
            ->whereNull('break_out')
            ->orderBy('created_at', 'desc')
            ->first();

        $breakOutTime = Carbon::now()->format('Y-m-d H:i:00');

        $breakDuration = Carbon::parse($break->break_in)->diffInMinutes($breakOutTime) / 60;

        $break->update([
            'break_out' => $breakOutTime,
            'break_duration' => round($breakDuration, 2),
        ]);

        AttendanceRecord::where('id', $request->attendance_record_id)
            ->update([
                'status' => '出勤中'
            ]);

        return redirect()->route('attendance.show');
    }
}

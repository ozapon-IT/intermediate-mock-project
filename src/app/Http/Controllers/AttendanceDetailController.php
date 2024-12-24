<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AttendanceDetailController extends Controller
{
    public function show($id)
    {
        $attendanceRecord = AttendanceRecord::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $attendanceRecord->formatted_year = Carbon::parse($attendanceRecord->date)->format('Y年');
        $attendanceRecord->formatted_month_day = Carbon::parse($attendanceRecord->date)->format('m月d日');
        $attendanceRecord->formatted_clock_in = Carbon::parse($attendanceRecord->clock_in)->format('H:i');
        $attendanceRecord->formatted_clock_out = Carbon::parse($attendanceRecord->clock_out)->format('H:i');

        $breaks = $attendanceRecord->breaks;

        foreach ($breaks as $break) {
            $break->formatted_break_in = Carbon::parse($break->break_in)->format('H:i');
            $break->formatted_break_out = Carbon::parse($break->break_out)->format('H:i');
        }

        return view('attendance-detail', compact('attendanceRecord', 'breaks'));
    }
}

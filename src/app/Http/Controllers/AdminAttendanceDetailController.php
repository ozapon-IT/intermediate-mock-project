<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;
use App\Models\AttendanceCorrection;

class AdminAttendanceDetailController extends Controller
{
    public function show($id)
    {
        $attendanceRecord = AttendanceRecord::findOrFail($id);

        $attendanceRecord->formatted_year = Carbon::parse($attendanceRecord->date)->format('Y年');
        $attendanceRecord->formatted_month_day = Carbon::parse($attendanceRecord->date)->format('m月d日');
        $attendanceRecord->formatted_clock_in = $attendanceRecord->clock_in ? Carbon::parse($attendanceRecord->clock_in)->format('H:i') : '';
        $attendanceRecord->formatted_clock_out = $attendanceRecord->clock_out ? Carbon::parse($attendanceRecord->clock_out)->format('H:i') : '';

        $breaks = $attendanceRecord->attendanceBreaks;

        foreach ($breaks as $break) {
            $break->formatted_break_in = $break->break_in ? Carbon::parse($break->break_in)->format('H:i') : '';
            $break->formatted_break_out = $break->break_out ? Carbon::parse($break->break_out)->format('H:i') : '';
        }

        $attendanceCorrection = AttendanceCorrection::where('attendance_record_id', $id)->where('user_id', $attendanceRecord->user->id)->latest()->first();
        if ($attendanceCorrection) {
            $attendanceCorrection->formatted_year = $attendanceCorrection->new_date ? Carbon::parse($attendanceCorrection->new_date)->format('Y年') : '';
            $attendanceCorrection->formatted_month_day = $attendanceCorrection->new_date ? Carbon::parse($attendanceCorrection->new_date)->format('m月d日') : '';
            $attendanceCorrection->formatted_new_clock_in = $attendanceCorrection->new_clock_in ? Carbon::parse($attendanceCorrection->new_clock_in)->format('H:i') : '';
            $attendanceCorrection->formatted_new_clock_out = $attendanceCorrection->new_clock_out ? Carbon::parse($attendanceCorrection->new_clock_out)->format('H:i') : '';

            $breakCorrections = $attendanceCorrection->breakCorrections;
            foreach ($breakCorrections as $breakCorrection) {
                $breakCorrection->formatted_new_break_in = $breakCorrection->new_break_in ? Carbon::parse($breakCorrection->new_break_in)->format('H:i') : '';
                $breakCorrection->formatted_new_break_out = $breakCorrection->new_break_out ? Carbon::parse($breakCorrection->new_break_out)->format('H:i') : '';
            }
        } else {
            $breakCorrections = [];
        }

        $isWaitingApproval = $attendanceCorrection && $attendanceCorrection->status === '承認待ち';

        return view('admin.attendance-detail', compact('attendanceRecord', 'breaks', 'attendanceCorrection', 'breakCorrections', 'isWaitingApproval'));
    }
}

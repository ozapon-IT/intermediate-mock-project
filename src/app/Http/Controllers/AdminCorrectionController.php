<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AdminCorrectionController extends Controller
{
    public function correct(AttendanceCorrectionRequest $request, $id)
    {
        $attendanceRecord = AttendanceRecord::findOrFail($id);
        $attendanceBreaks = $attendanceRecord->attendanceBreaks ?? [];

        $formattedDate = Carbon::createFromFormat('Y年m月d日', "{$request->year}{$request->month_day}")->toDateString();

        $attendanceRecord->update([
            'date' => $formattedDate,
            'clock_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$request->clock_in}"),
            'clock_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$request->clock_out}"),
        ]);

        foreach ($attendanceBreaks as $index => $attendanceBreak) {
            $breakIn = $request->break_in[$index];
            $breakOut = $request->break_out[$index];
            $breakDuration = Carbon::parse($breakIn)->diffInMinutes($breakOut) / 60;

            if ($breakIn && $breakOut) {
                $attendanceBreak->update([
                    'break_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$breakIn}"),
                    'break_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$breakOut}"),
                    'break_duration' => round($breakDuration, 2),
                ]);
            }
        }

        $attendanceRecord->update([
            'break_hours' => $attendanceRecord->calculateBreakHours(),
            'work_hours' => $attendanceRecord->calculateWorkHours(),
        ]);

        $monthlyAttendance = Carbon::parse($attendanceRecord->date)->format('Y-m');

        return redirect()->route('admin.staff-attendance-list.show', ['id' => $attendanceRecord->user_id, 'month' => $monthlyAttendance]);
    }
}

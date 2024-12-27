<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AttendanceRecord;

class AdminAttendanceListController extends Controller
{
    public function show(Request $request)
    {
        $currentDay = $request->input('day', now()->format('Y年m月d日'));
        $currentDayObj = Carbon::createFromFormat('Y年m月d日', $currentDay);
        $currentDayFormatted = $currentDayObj->format('Y/m/d');
        $previousDay = $currentDayObj->copy()->subDay()->format('Y年m月d日');
        $nextDay = $currentDayObj->copy()->addDay()->format('Y年m月d日');

        $attendanceRecords = AttendanceRecord::with('user')->where('date', $currentDayObj->format('Y-m-d'))->get();

        foreach ($attendanceRecords as $record) {
            $record->formatted_clock_in = $record->clock_in ? Carbon::parse($record->clock_in)->format('H:i') : '';
            $record->formatted_clock_out = $record->clock_out ? Carbon::parse($record->clock_out)->format('H:i') : '';
            $record->formatted_break_hours = $record->formatBreakHours();
            $record->formatted_work_hours = $record->formatWorkHours();
        }

        return view('admin.attendance-list', compact('currentDay', 'attendanceRecords', 'currentDayFormatted', 'previousDay', 'nextDay'));
    }
}

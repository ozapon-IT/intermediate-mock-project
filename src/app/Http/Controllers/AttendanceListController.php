<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AttendanceListController extends Controller
{
    public function show(Request $request)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $attendanceRecords = AttendanceRecord::where('user_id', auth()->id())->whereBetween('date', [
            Carbon::parse($currentMonth)->startOfMonth(),
            Carbon::parse($currentMonth)->endOfMonth()
        ])->orderBy('date')->get();

        foreach ($attendanceRecords as $record) {
            $record->formatted_date = Carbon::parse($record->date)->locale('ja')->isoFormat('MM/DD(ddd)');
            $record->formatted_clock_in = $record->clock_in ? Carbon::parse($record->clock_in)->format('H:i') : '';
            $record->formatted_clock_out = $record->clock_out ? Carbon::parse($record->clock_out)->format('H:i') : '';
            $record->formatted_break_hours = $record->formatBreakHours();
            $record->formatted_work_hours = $record->formatWorkHours();
        }

        $currentMonthFormatted = Carbon::parse($currentMonth)->format('Y/m');
        $previousMonth = Carbon::parse($currentMonth)->subMonth()->format('Y-m');
        $nextMonth = Carbon::parse($currentMonth)->addMonth()->format('Y-m');

        return view('attendance-list', compact('attendanceRecords', 'currentMonthFormatted', 'previousMonth', 'nextMonth'));
    }
}

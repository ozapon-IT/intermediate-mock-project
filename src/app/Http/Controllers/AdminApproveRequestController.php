<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrectRequest;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class AdminApproveRequestController extends Controller
{
    public function show(AttendanceCorrectRequest $attendanceCorrectRequest)
    {
        $attendanceCorrection = $attendanceCorrectRequest;
        $attendanceCorrection->formatted_year = $attendanceCorrection->new_date ? Carbon::parse($attendanceCorrection->new_date)->format('Y年') : '';
        $attendanceCorrection->formatted_month_day = $attendanceCorrection->new_date ? Carbon::parse($attendanceCorrection->new_date)->format('m月d日') : '';
        $attendanceCorrection->formatted_new_clock_in = $attendanceCorrection->new_clock_in ? Carbon::parse($attendanceCorrection->new_clock_in)->format('H:i') : '';
        $attendanceCorrection->formatted_new_clock_out = $attendanceCorrection->new_clock_out ? Carbon::parse($attendanceCorrection->new_clock_out)->format('H:i') : '';

        $attendanceBreaks = $attendanceCorrection->breakCorrectRequests;
        foreach ($attendanceBreaks as $attendanceBreak) {
            $attendanceBreak->formatted_new_break_in = $attendanceBreak->new_break_in ? Carbon::parse($attendanceBreak->new_break_in)->format('H:i') : '';
            $attendanceBreak->formatted_new_break_out = $attendanceBreak->new_break_out ? Carbon::parse($attendanceBreak->new_break_out)->format('H:i') : '';
        }

        $isWaitingApproval = $attendanceCorrection->status === '承認待ち';

        return view('admin.approve-request', compact('attendanceCorrection', 'attendanceBreaks', 'isWaitingApproval'));
    }

    public function approve(AttendanceCorrectRequest $attendanceCorrectRequest, Request $request)
    {
        $attendanceCorrectRequest->update([
            'status' => '承認済み',
        ]);

        $attendanceRecord = $attendanceCorrectRequest->attendanceRecord;
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

            if (!$breakIn || !$breakOut) {
                continue;
            }

            $breakDuration = Carbon::parse($breakIn)->diffInMinutes($breakOut) / 60;
            $attendanceBreak->update([
                'break_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$breakIn}"),
                'break_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$breakOut}"),
                'break_duration' => round($breakDuration, 2),
            ]);
        }

        $attendanceRecord->update([
            'break_hours' => $attendanceRecord->calculateBreakHours(),
            'work_hours' => $attendanceRecord->calculateWorkHours(),
        ]);

        return redirect()->route('admin.request-list.show', ['status' => '承認済み']);
    }
}

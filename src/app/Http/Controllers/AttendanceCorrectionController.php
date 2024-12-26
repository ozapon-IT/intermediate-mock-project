<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;
use App\Models\AttendanceCorrection;
use Illuminate\Support\Facades\Auth;

class AttendanceCorrectionController extends Controller
{
    public function requestCorrection(AttendanceCorrectionRequest $request, $id)
    {
        $attendanceRecord = AttendanceRecord::findOrFail($id);

        $newDate = Carbon::createFromFormat('Y年m月d日', "{$request->year}{$request->month_day}");

        $attendanceCorrection = AttendanceCorrection::create([
            'attendance_record_id' => $attendanceRecord->id,
            'user_id' => Auth::id(),
            'requested_date' => Carbon::today(),
            'old_date' => $attendanceRecord->date,
            'new_date' => $newDate->toDateString(),
            'old_clock_in' => $attendanceRecord->clock_in,
            'old_clock_out' => $attendanceRecord->clock_out,
            'new_clock_in' => $request->clock_in,
            'new_clock_out' =>$request->clock_out,
            'reason' => $request->reason,
            'status' => '承認待ち',
        ]);

        if ($request->has('break_in') && $request->has('break_out')) {
            foreach ($request->break_in as $index => $breakIn) {
                $breakOut = $request->break_out[$index];

                $oldBreak = $attendanceRecord->attendanceBreaks[$index];

                if ($breakIn && $breakOut) {
                    $attendanceCorrection->breakCorrections()->create([
                        'attendance_break_id' => $oldBreak->id,
                        'old_break_in' => $oldBreak->break_in,
                        'old_break_out' => $oldBreak->break_out,
                        'new_break_in' => $breakIn,
                        'new_break_out' =>$breakOut,
                    ]);
                }
            }
        }

        return redirect()->route('attendance-detail.wait_approval', $attendanceRecord->id);
    }

    public function waitApproval($id)
    {
        $attendanceRecord = AttendanceRecord::findOrFail($id);

        $attendanceCorrection = AttendanceCorrection::where('attendance_record_id', $id)->where('user_id', Auth::id())->latest()->first();
        $attendanceCorrection->formatted_year = Carbon::parse($attendanceCorrection->new_date)->format('Y年');
        $attendanceCorrection->formatted_month_day = Carbon::parse($attendanceCorrection->new_date)->format('m月d日');
        $attendanceCorrection->formatted_new_clock_in = $attendanceCorrection->new_clock_in ? Carbon::parse($attendanceCorrection->new_clock_in)->format('H:i') : '';
        $attendanceCorrection->formatted_new_clock_out = $attendanceCorrection->new_clock_out ? Carbon::parse($attendanceCorrection->new_clock_out)->format('H:i') : '';

        $breakCorrections = $attendanceCorrection->breakCorrections;

        foreach ($breakCorrections as $breakCorrection) {
            $breakCorrection->formatted_new_break_in = $breakCorrection->new_break_in ? Carbon::parse($breakCorrection->new_break_in)->format('H:i') : '';
            $breakCorrection->formatted_new_break_out = $breakCorrection->new_break_out ? Carbon::parse($breakCorrection->new_break_out)->format('H:i') : '';
        }

        $isWaitingApproval = $attendanceCorrection && $attendanceCorrection->status === '承認待ち';

        return view('attendance-detail', compact('attendanceRecord', 'attendanceCorrection', 'breakCorrections', 'isWaitingApproval'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;
use App\Models\AttendanceCorrectRequest;

class AttendanceCorrectionController extends Controller
{
    public function requestCorrection(AttendanceCorrectionRequest $request, $id)
    {
        $attendanceRecord = AttendanceRecord::findOrFail($id);

        $formattedDate = Carbon::createFromFormat('Y年m月d日', "{$request->year}{$request->month_day}")->toDateString();

        $attendanceCorrection = AttendanceCorrectRequest::create([
            'attendance_record_id' => $attendanceRecord->id,
            'user_id' => $attendanceRecord->user->id,
            'requested_date' => Carbon::today(),
            'old_date' => $attendanceRecord->date,
            'new_date' => $formattedDate,
            'old_clock_in' => $attendanceRecord->clock_in,
            'old_clock_out' => $attendanceRecord->clock_out,
            'new_clock_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$request->clock_in}"),
            'new_clock_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$request->clock_out}"),
            'reason' => $request->reason,
            'status' => '承認待ち',
        ]);

        if ($request->has('break_in') && $request->has('break_out')) {
            foreach ($request->break_in as $index => $breakIn) {
                $breakOut = $request->break_out[$index];

                $oldBreak = $attendanceRecord->attendanceBreaks[$index];

                if ($breakIn && $breakOut) {
                    $attendanceCorrection->breakCorrectRequests()->create([
                        'attendance_break_id' => $oldBreak->id,
                        'old_break_in' => $oldBreak->break_in,
                        'old_break_out' => $oldBreak->break_out,
                        'new_break_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$breakIn}"),
                        'new_break_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate}  {$breakOut}"),
                    ]);
                }
            }
        }

        return redirect()->route('request-list.show');
    }
}

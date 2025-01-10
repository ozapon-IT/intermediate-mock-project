<?php

namespace App\Http\Controllers;

use App\Services\AttendanceCorrectionService;
use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AdminCorrectionController extends Controller
{
    protected $attendanceCorrectionService;

    public function __construct(AttendanceCorrectionService $attendanceCorrectionService)
    {
        $this->attendanceCorrectionService = $attendanceCorrectionService;
    }

    public function correct(AttendanceCorrectionRequest $request, $id)
    {
        $attendanceRecord = AttendanceRecord::findOrFail($id);

        $this->attendanceCorrectionService->correctAttendanceRecord($request->validated(), $attendanceRecord);

        $monthlyAttendance = Carbon::parse($attendanceRecord->date)->format('Y-m');

        return redirect()->route('admin.staff-attendance-list.show', ['id' => $attendanceRecord->user_id, 'month' => $monthlyAttendance]);
    }
}

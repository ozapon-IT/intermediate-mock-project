<?php

namespace App\Http\Controllers;

use App\Services\AttendanceCorrectionService;
use App\Http\Requests\AttendanceCorrectionRequest;

class AdminCorrectionController extends Controller
{
    protected $attendanceCorrectionService;

    public function __construct(AttendanceCorrectionService $attendanceCorrectionService)
    {
        $this->attendanceCorrectionService = $attendanceCorrectionService;
    }

    public function correct(AttendanceCorrectionRequest $request, $id)
    {
        $attendanceRecord = $this->attendanceCorrectionService->getAttendanceRecordById($id);

        $this->attendanceCorrectionService->correctAttendanceRecord($request->validated(), $attendanceRecord);

        $monthlyAttendance = $this->attendanceCorrectionService->formatDateToMonth($attendanceRecord->date);

        return redirect()->route('admin.staff-attendance-list.show', ['id' => $attendanceRecord->user_id, 'month' => $monthlyAttendance]);
    }
}
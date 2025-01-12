<?php

namespace App\Http\Controllers;

use App\Services\AttendanceCorrectionService;
use App\Http\Requests\AttendanceCorrectionRequest;

class AttendanceCorrectionController extends Controller
{
    protected $attendanceCorrectionService;

    public function __construct(AttendanceCorrectionService $attendanceCorrectionService)
    {
        $this->attendanceCorrectionService = $attendanceCorrectionService;
    }

    public function requestCorrection(AttendanceCorrectionRequest $request, $id)
    {
        $attendanceRecord = $this->attendanceCorrectionService->getAttendanceRecordById($id);

        $this->attendanceCorrectionService->createAttendanceCorrection($request->validated(), $attendanceRecord);

        return redirect()->route('request-list.show');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\AttendanceRecord;
use App\Services\AttendanceCorrectionService;

class AttendanceCorrectionController extends Controller
{
    protected $attendanceCorrectionService;

    public function __construct(AttendanceCorrectionService $attendanceCorrectionService)
    {
        $this->attendanceCorrectionService = $attendanceCorrectionService;
    }

    public function requestCorrection(AttendanceCorrectionRequest $request, $id)
    {
        $attendanceRecord = AttendanceRecord::findOrFail($id);

        $this->attendanceCorrectionService->createAttendanceCorrection($request->validated(), $attendanceRecord);

        return redirect()->route('request-list.show');
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\AttendanceDetailService;

class AttendanceDetailController extends Controller
{
    protected $attendanceDetailService;

    public function __construct(AttendanceDetailService $attendanceDetailService)
    {
        $this->attendanceDetailService = $attendanceDetailService;
    }

    public function show($id)
    {
        $attendanceData = $this->attendanceDetailService->getFormattedAttendanceRecord($id, auth()->id());

        $attendanceCorrection = $this->attendanceDetailService->getAttendanceCorrection($id);

        $correctionData = $this->attendanceDetailService->getFormattedCorrectionData($attendanceCorrection);

        $isWaitingApproval = $this->attendanceDetailService->isWaitingApproval($correctionData['attendanceCorrection']);

        return view('attendance-detail', array_merge($attendanceData, $correctionData, compact('isWaitingApproval')));
    }
}

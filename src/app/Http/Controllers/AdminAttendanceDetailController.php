<?php

namespace App\Http\Controllers;

use App\Services\AttendanceDetailService;
use App\Models\AttendanceCorrectRequest;

class AdminAttendanceDetailController extends Controller
{
    protected $attendanceDetailService;

    public function __construct(AttendanceDetailService $attendanceDetailService)
    {
        $this->attendanceDetailService = $attendanceDetailService;
    }

    public function show($id)
    {
        $attendanceData = $this->attendanceDetailService->getFormattedAttendanceRecord($id);

        $attendanceCorrection = $this->attendanceDetailService->getAttendanceCorrection($id, $attendanceData['attendanceRecord']->user_id);

        $correctionData = $this->attendanceDetailService->getFormattedCorrectionData($attendanceCorrection);

        $isWaitingApproval = $this->attendanceDetailService->isWaitingApproval($correctionData['attendanceCorrection']);

        return view('admin.attendance-detail', array_merge($attendanceData, $correctionData, compact('isWaitingApproval')));
    }
}

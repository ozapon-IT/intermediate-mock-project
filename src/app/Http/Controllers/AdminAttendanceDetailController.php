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

        $attendanceCorrection = AttendanceCorrectRequest::where('attendance_record_id', $id)
            ->where('user_id', $attendanceData['attendanceRecord']->user_id)
            ->latest()
            ->first();

        $correctionData = $attendanceCorrection
            ? $this->attendanceDetailService->formatAttendanceCorrection($attendanceCorrection)
            : ['attendanceCorrection' => null, 'breakCorrections' => []];

        $isWaitingApproval = $correctionData['attendanceCorrection'] && $correctionData['attendanceCorrection']->status === '承認待ち';

        return view('admin.attendance-detail', array_merge($attendanceData, $correctionData, compact('isWaitingApproval')));
    }
}

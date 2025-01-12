<?php

namespace App\Http\Controllers;

use App\Services\AttendanceDetailService;
use App\Services\AttendanceCorrectionService;
use App\Models\AttendanceCorrectRequest;
use Illuminate\Http\Request;

class AdminApproveRequestController extends Controller
{
    protected $attendanceDetailService;
    protected $attendanceCorrectionService;

    public function __construct(
        AttendanceDetailService $attendanceDetailService,
        AttendanceCorrectionService $attendanceCorrectionService
    ) {
        $this->attendanceDetailService = $attendanceDetailService;
        $this->attendanceCorrectionService = $attendanceCorrectionService;
    }

    public function show(AttendanceCorrectRequest $attendanceCorrectRequest)
    {
        $correctionData = $this->attendanceDetailService->formatAttendanceCorrection($attendanceCorrectRequest);

        $isWaitingApproval = $this->attendanceDetailService->isWaitingApproval($correctionData['attendanceCorrection']);

        return view('admin.approve-request', array_merge($correctionData, compact('isWaitingApproval')));
    }

    public function approve(AttendanceCorrectRequest $attendanceCorrectRequest, Request $request)
    {
        $data = $request->all();

        $this->attendanceCorrectionService->approveAttendanceCorrection($attendanceCorrectRequest, $data);

        return redirect()->route('admin.request-list.show', ['status' => '承認済み']);
    }
}
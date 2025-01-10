<?php

namespace App\Http\Controllers;

use App\Services\AttendanceDetailService;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceCorrectRequest;

class AttendanceDetailController extends Controller
{
    protected $attendanceDetailService;

    public function __construct(AttendanceDetailService $attendanceDetailService)
    {
        $this->attendanceDetailService = $attendanceDetailService;
    }

    public function show($id)
    {
        $user = Auth::guard('web')->user();
        $attendanceData = $this->attendanceDetailService->getFormattedAttendanceRecord($id, $user->id);

        $attendanceCorrection = AttendanceCorrectRequest::where('attendance_record_id', $id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $correctionData = $attendanceCorrection
            ? $this->attendanceDetailService->formatAttendanceCorrection($attendanceCorrection)
            : ['attendanceCorrection' => null, 'breakCorrections' => []];

        $isWaitingApproval = $correctionData['attendanceCorrection'] && $correctionData['attendanceCorrection']->status === '承認待ち';

        return view('attendance-detail', array_merge($attendanceData, $correctionData, compact('isWaitingApproval')));
    }
}

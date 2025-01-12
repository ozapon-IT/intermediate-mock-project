<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceBreakController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function breakIn(Request $request)
    {
        $this->attendanceService->breakIn($request->attendance_record_id);

        return redirect()->route('attendance.show');
    }

    public function breakOut(Request $request)
    {
        $this->attendanceService->breakOut($request->attendance_record_id);

        return redirect()->route('attendance.show');
    }
}

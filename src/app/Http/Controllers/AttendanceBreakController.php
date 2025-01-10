<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceService;
// use App\Models\AttendanceBreak;
// use Carbon\Carbon;
// use App\Models\AttendanceRecord;


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

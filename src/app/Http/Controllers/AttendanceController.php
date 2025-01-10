<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function show()
    {
        $attendanceInformation = $this->attendanceService->getTodayAttendanceInformation(auth()->id());

        return view('attendance', array_merge($attendanceInformation));
    }

    public function clockIn()
    {
        $this->attendanceService->clockIn(auth()->id());

        return redirect()->route('attendance.show');
    }

    public function clockOut()
    {
        $this->attendanceService->clockOut(auth()->id());

        return redirect()->route('attendance.show');
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\AttendanceListService;
use Illuminate\Http\Request;

class AttendanceListController extends Controller
{
    protected $attendanceListService;

    public function __construct(AttendanceListService $attendanceListService)
    {
        $this->attendanceListService = $attendanceListService;
    }

    public function show(Request $request)
    {
        $currentMonth = $this->attendanceListService->getCurrentMonth($request);

        $attendanceRecords = $this->attendanceListService->getFormattedAttendanceRecords(auth()->id(), $currentMonth);

        $monthNavigation = $this->attendanceListService->getMonthNavigation($currentMonth);

        return view('attendance-list', array_merge(['attendanceRecords' => $attendanceRecords], $monthNavigation));
    }
}

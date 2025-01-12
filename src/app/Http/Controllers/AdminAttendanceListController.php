<?php

namespace App\Http\Controllers;

use App\Services\AttendanceListService;
use Illuminate\Http\Request;

class AdminAttendanceListController extends Controller
{
    protected $attendanceListService;

    public function __construct(AttendanceListService $attendanceListService)
    {
        $this->attendanceListService = $attendanceListService;
    }

    public function show(Request $request)
    {
        $currentDay = $this->attendanceListService->getCurrentDay($request);

        $attendanceRecords = $this->attendanceListService->getDailyFormattedAttendanceRecords($currentDay);

        $dayNavigation = $this->attendanceListService->getDayNavigation($currentDay);

        return view('admin.attendance-list', array_merge(
            ['currentDay' => $currentDay, 'attendanceRecords' => $attendanceRecords],
            $dayNavigation
        ));
    }
}

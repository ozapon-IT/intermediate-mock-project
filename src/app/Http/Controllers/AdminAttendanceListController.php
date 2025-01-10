<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceListService;

class AdminAttendanceListController extends Controller
{
    protected $attendanceListService;

    public function __construct(AttendanceListService $attendanceListService)
    {
        $this->attendanceListService = $attendanceListService;
    }

    public function show(Request $request)
    {
        $currentDay = $request->input('day', now()->format('Y年n月j日'));

        $attendanceRecords = $this->attendanceListService->getDailyFormattedAttendanceRecords($currentDay);

        $dayNavigation = $this->attendanceListService->getDayNavigation($currentDay);

        return view('admin.attendance-list', array_merge(
            ['currentDay' => $currentDay, 'attendanceRecords' => $attendanceRecords],
            $dayNavigation
        ));
    }
}

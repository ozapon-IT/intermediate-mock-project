<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceListService;

class AttendanceListController extends Controller
{
    protected $attendanceListService;

    public function __construct(AttendanceListService $attendanceListService)
    {
        $this->attendanceListService = $attendanceListService;
    }

    public function show(Request $request)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $attendanceRecords = $this->attendanceListService->getFormattedAttendanceRecords(auth()->id(), $currentMonth);
        $monthNavigation = $this->attendanceListService->getMonthNavigation($currentMonth);

        return view('attendance-list', array_merge(
            ['attendanceRecords' => $attendanceRecords],
            $monthNavigation
        ));
    }
}

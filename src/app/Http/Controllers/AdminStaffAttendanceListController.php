<?php

namespace App\Http\Controllers;

use App\Services\AttendanceListService;
use Illuminate\Http\Request;

class AdminStaffAttendanceListController extends Controller
{
    protected $attendanceListService;

    public function __construct(AttendanceListService $attendanceListService)
    {
        $this->attendanceListService = $attendanceListService;
    }

    public function show(Request $request, $id)
    {
        $currentMonth = $this->attendanceListService->getCurrentMonth($request);

        $attendanceRecords = $this->attendanceListService->getFormattedAttendanceRecords($id, $currentMonth);

        $monthNavigation = $this->attendanceListService->getMonthNavigation($currentMonth);

        $user = $this->attendanceListService->getUserById($id);

        return view('admin.staff-attendance-list', array_merge([
            'attendanceRecords' => $attendanceRecords, 'user' => $user],
            $monthNavigation,
            ['currentMonth' => $currentMonth]
        ));
    }

    public function export(Request $request, $id)
    {
        $currentMonth = $this->attendanceListService->getCurrentMonth($request);

        $attendanceRecords = $this->attendanceListService->getFormattedAttendanceRecords($id, $currentMonth);

        $callback = $this->attendanceListService->exportAttendanceRecordsToCsv($attendanceRecords);

        $user = $this->attendanceListService->getUserById($id);

        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=月次勤怠_{$user->name}_$currentMonth.csv",
        ]);
    }
}

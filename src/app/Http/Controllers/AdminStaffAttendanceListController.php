<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceListService;
use App\Models\User;

class AdminStaffAttendanceListController extends Controller
{
    protected $attendanceListService;

    public function __construct(AttendanceListService $attendanceListService)
    {
        $this->attendanceListService = $attendanceListService;
    }

    public function show(Request $request, $id)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $attendanceRecords = $this->attendanceListService->getFormattedAttendanceRecords($id, $currentMonth);
        $monthNavigation = $this->attendanceListService->getMonthNavigation($currentMonth);

        $user = User::findOrFail($id);

        return view('admin.staff-attendance-list', array_merge(
            ['attendanceRecords' => $attendanceRecords, 'user' => $user],
            $monthNavigation,
            ['currentMonth' => $currentMonth]
        ));
    }

    public function export(Request $request, $id)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $attendanceRecords = $this->attendanceListService->getFormattedAttendanceRecords($id, $currentMonth);
        $callback = $this->attendanceListService->exportAttendanceRecordsToCsv($attendanceRecords);
        $user = User::findOrFail($id);

        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=月次勤怠_{$user->name}_$currentMonth.csv",
        ]);
    }
}

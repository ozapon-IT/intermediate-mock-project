<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceCorrection;
use Illuminate\Support\Carbon;

class AdminRequestListController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', '承認待ち');

        $attendanceCorrections = AttendanceCorrection::where('status', $status)->get();

        foreach ($attendanceCorrections as $attendanceCorrection) {
            $attendanceCorrection->formatted_requested_date = Carbon::parse($attendanceCorrection->requested_date)->isoFormat('YYYY/MM/DD');
            $attendanceCorrection->formatted_old_date = Carbon::parse($attendanceCorrection->old_date)->isoFormat('YYYY/MM/DD');
        }

        return view('admin.request-list', compact('attendanceCorrections', 'status'));
    }
}

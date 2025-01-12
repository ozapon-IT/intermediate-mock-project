<?php

namespace App\Http\Controllers;

use App\Services\RequestListService;
use Illuminate\Http\Request;

class AdminRequestListController extends Controller
{
    protected $requestListService;

    public function __construct(RequestListService $requestListService)
    {
        $this->requestListService = $requestListService;
    }

    public function show(Request $request)
    {
        $status = $this->requestListService->getStatusFromQuery($request);

        $attendanceCorrections = $this->requestListService->getFormattedAttendanceCorrections($status);

        return view('admin.request-list', compact('attendanceCorrections', 'status'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RequestListService;

class RequestListController extends Controller
{
    protected $requestListService;

    public function __construct(RequestListService $requestListService)
    {
        $this->requestListService = $requestListService;
    }

    public function show(Request $request)
    {
        $status = $request->query('status', '承認待ち');

        $attendanceCorrections = $this->requestListService->getFormattedAttendanceCorrections($status, auth()->id());

        return view('request-list', compact('attendanceCorrections', 'status'));
    }
}

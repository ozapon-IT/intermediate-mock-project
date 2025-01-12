<?php

namespace App\Http\Controllers;

use App\Services\RequestListService;
use Illuminate\Http\Request;

class RequestListController extends Controller
{
    protected $requestListService;

    public function __construct(RequestListService $requestListService)
    {
        $this->requestListService = $requestListService;
    }

    public function show(Request $request)
    {
        $status = $this->requestListService->getStatusFromQuery($request);

        $attendanceCorrections = $this->requestListService->getFormattedAttendanceCorrections($status, auth()->id());

        return view('request-list', compact('attendanceCorrections', 'status'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminApproveRequestController extends Controller
{
    public function show()
    {
        return view('admin.approve-request');
    }
}

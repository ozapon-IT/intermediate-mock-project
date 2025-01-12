<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminStaffListController extends Controller
{
    public function show()
    {
        $users = User::roleUser()->get();

        return view('admin.staff-list', compact('users'));
    }
}
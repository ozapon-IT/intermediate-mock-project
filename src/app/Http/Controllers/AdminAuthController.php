<?php

namespace App\Http\Controllers;

use App\Services\AdminAuthService;
use App\Http\Requests\CustomLoginRequest;

class AdminAuthController extends Controller
{
    protected $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function show()
    {
        return view('auth.admin-login');
    }

    public function login(CustomLoginRequest $request)
    {
        $this->adminAuthService->login($request->email, $request->password);

        return redirect()->route('admin.attendance-list.show');
    }

    public function logout()
    {
        $this->adminAuthService->logout();

        return redirect()->route('admin-login.show');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function show()
    {
        return view('auth.admin-login');
    }

    public function login(AdminLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard('admin')->login($user);

            return redirect()->route('admin.attendance-list');
        }

        throw ValidationException::withMessages([
            'email' => ['ログイン情報が登録されていません。'],
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin-login.show');
    }
}

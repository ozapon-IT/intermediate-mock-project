<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthService
{
    /**
     * 管理者のログイン処理
     *
     * @param string $email
     * @param string $password
     * @return void
     * @throws ValidationException
     */
    public function login(string $email, string $password): void
    {
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('admin')->login($user);
            return;
        }

        throw ValidationException::withMessages([
            'email' => ['ログイン情報が登録されていません'],
        ]);
    }

    /**
     * 管理者のログアウト処理
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
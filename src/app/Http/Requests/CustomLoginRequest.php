<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomLoginRequest extends LoginRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:8|max:100',

        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.string' => 'メールアドレスを文字列で入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'email.max' => 'メールアドレスは100文字以下で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.string' => 'パスワードを文字列で入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは100文字以下で入力してください',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $redirectRoute = 'login';

        if ($this->isAdminGuard()) {
            $redirectRoute = 'admin-login.show';
        }

        $response = redirect()
            ->route($redirectRoute)
            ->withErrors($validator)
            ->withInput();

        // dd($validator->errors()->messages());

        throw new HttpResponseException($response);
    }

    protected function isAdminGuard() : bool
    {
        return auth('admin')->check() || $this->routeIs('admin-*');
    }
}

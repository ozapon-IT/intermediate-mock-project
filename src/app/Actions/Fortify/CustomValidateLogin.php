<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\CustomLoginRequest;

class CustomValidateLogin
{
    /**
     * カスタムルールとメッセージを使用してログインリクエストを検証
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request): void
    {
        $loginRequest = app(CustomLoginRequest::class);
        $rules = $loginRequest->rules();
        $messages = $loginRequest->messages();

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }
}
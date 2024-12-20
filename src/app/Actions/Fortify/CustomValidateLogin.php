<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\CustomLoginRequest;

class CustomValidateLogin
{
    public function __invoke(Request $request)
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
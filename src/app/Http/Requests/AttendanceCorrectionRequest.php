<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AttendanceCorrectionRequest extends FormRequest
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
            'year' => 'required|regex:/^\d{4}年$/',
            'month_day' => 'required|regex:/^\d{1,2}月\d{1,2}日$/',
            'clock_in' => 'required|date_format:H:i|before:clock_out',
            'clock_out' => 'required|date_format:H:i',
            'break_in.*' => 'required|date_format:H:i|after_or_equal:clock_in|before:break_out.*',
            'break_out.*' => 'required|date_format:H:i|before_or_equal:clock_out',
            'reason' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'year.required' => '年を入力してください',
            'year.regex' => '○○○○年のように数字4桁と年を入力してください（例 2025年）',
            'month_day.required' => '月日を入力してください',
            'month_day.regex' => '○月○日のように数字1~2桁と月日を入力してください（例 1月1日）',
            'clock_in.required' => '出勤時間を入力してください',
            'clock_in.date_format' => '出勤時間は○○:○○のように入力してください（例 09:00）',
            'clock_in.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'clock_out.required' => '退勤時間を入力してください',
            'clock_out.date_format' => '退勤時間は○○:○○のように入力してください（例 18:00）',
            'break_in.*.required' => '休憩入時間を入力してください',
            'break_in.*.date_format' => '休憩入時間は○○:○○のように入力してください（例 12:00）',
            'break_in.*.after_or_equal' => '休憩時間が勤務時間外です',
            'break_in.*.before' => '休憩入時間もしくは休憩戻時間が不適切な値です',
            'break_out.*.required' => '休憩戻時間を入力してください',
            'break_out.*.date_format' => '休憩戻時間は○○:○○のように入力してください（例 13:00）',
            'break_out.*.before_or_equal' => '休憩時間が勤務時間外です',
            'reason.required' => '備考を記入してください',
            'reason.string' => '備考を文字列で入力してください',
            'reason.max' => '備考は100文字以下で入力してください',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $redirectRoute = 'attendance-detail.show';

        if ($this->isAdminGuard()) {
            $redirectRoute = 'admin.attendance-detail.show';
        }

        $response = redirect()
            ->route($redirectRoute, ['id' => $this->route('id')])
            ->withErrors($validator)
            ->withInput();

        throw new HttpResponseException($response);
    }

    protected function isAdminGuard() : bool
    {
        return auth('admin')->check() || $this->routeIs('admin-*');
    }
}

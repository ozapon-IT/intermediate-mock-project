<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'year' => 'required|numeric|digits:4',
            'month_day' => 'required|string|regex:/^\d{1,2}月\d{1,2}日$/',
            'clock_in' => 'required|date_format:H:i|before:clock_out',
            'clock_out' => 'required|date_format:H:i|after:clock_in',
            'break_in.*' => 'nullable|date_format:H:i|after_or_equal:clock_in|before:clock_out',
            'break_out.*' => 'nullable|date_format:H:i|after:break_in.*|before_or_equal:clock_out',
            'reason' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'clock_in.before' => '出勤時間もしくは退勤時間が不適切な値です。',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です。',
            'break_in.*.after_or_equal' => '休憩時間が勤務時間外です。',
            'break_out.*.before_or_equal' => '休憩時間が勤務時間外です。',
            'reason.required' => '備考を記入してください。',
        ];
    }
}

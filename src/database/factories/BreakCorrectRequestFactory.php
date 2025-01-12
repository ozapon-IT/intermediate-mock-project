<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\AttendanceCorrectRequest;
use App\Models\AttendanceBreak;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BreakCorrectRequest>
 */
class BreakCorrectRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $breakIn = Carbon::now()->setHour(12)->setMinute(0)->setSecond(0);
        $breakOut = $breakIn->copy()->addHours(1);

        return [
            'attendance_correct_request_id' => AttendanceCorrectRequest::factory(),
            'attendance_break_id' => AttendanceBreak::factory(),
            'old_break_in' => $breakIn->toDateTimeString(),
            'old_break_out' => $breakOut->toDateTimeString(),
            'new_break_in' => $breakIn->copy()->addMinutes(30)->toDateTimeString(),
            'new_break_out' => $breakOut->copy()->addMinutes(30)->toDateTimeString(),
        ];
    }
}

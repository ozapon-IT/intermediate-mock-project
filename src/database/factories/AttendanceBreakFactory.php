<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\AttendanceRecord;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceBreak>
 */
class AttendanceBreakFactory extends Factory
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
            'attendance_record_id' => AttendanceRecord::factory(),
            'break_in' => $breakIn->toDateTimeString(),
            'break_out' => $breakOut->toDateTimeString(),
            'break_duration' => round(($breakOut->diffInMinutes($breakIn)) / 60, 2),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\AttendanceRecord;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceCorrectRequest>
 */
class AttendanceCorrectRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = Carbon::now()->setHour(9)->setMinute(0)->setSecond(0);

        return [
            'attendance_record_id' => AttendanceRecord::factory(),
            'user_id' => User::factory(),
            'requested_date' => $now->copy()->format('Y-m-d'),
            'old_date' => $now->copy()->subDay(7)->format('Y-m-d'),
            'new_date' => $now->copy()->subDay(7)->format('Y-m-d'),
            'old_clock_in' => $now->copy()->subDay(7)->toDateTimeString(),
            'old_clock_out' => $now->copy()->subDay(7)->addHours(9)->toDateTimeString(),
            'new_clock_in' => $now->copy()->subDay(7)->addHours(1)->toDateTimeString(),
            'new_clock_out' => $now->copy()->subDay(7)->addHours(10)->toDateTimeString(),
            'reason' => $this->faker->text(100),
            'status' => '承認待ち',
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clockIn = Carbon::now();
        $clockOut = Carbon::now()->addHours(9);;

        return [
            'user_id' => User::factory(),
            'date' => Carbon::now()->format('Y-m-d'),
            'clock_in' => $clockIn->toDateTimeString(),
            'clock_out' => $clockOut->toDateTimeString(),
        ];
    }

    public function clockIn()
    {
        return $this->state([
            'status' => '出勤中',
        ]);
    }

    public function breaks()
    {
        return $this->state([
            'status' => '休憩中',
        ]);
    }

    public function clockOut()
    {
        return $this->state([
            'status' => '退勤済',
        ]);
    }
}

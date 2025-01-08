<?php

namespace Tests\Traits;

use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;

trait AttendanceTestHelper
{
    /**
     * 勤怠データを作成するヘルパー関数
     */
    public function createAttendanceRecord(int $userId, string $date, string $clockIn, string $clockOut, array $breaks = []): void
    {
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $userId,
            'date' => $date,
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
        ]);

        foreach ($breaks as $breakData) {
            AttendanceBreak::factory()->create(array_merge([
                'attendance_record_id' => $attendanceRecord->id,
            ], $breakData));
        }
    }

    /**
     * 勤怠情報が正しく表示されていることを確認するヘルパー関数
     */
    public function assertAttendanceDisplayed($response, array $attendanceData): void
    {
        foreach ($attendanceData as $data) {
            if (isset($data['year'])) {
                $response->assertSee($data['year']);
            }

            if (isset($data['year_month'])) {
                $response->assertSee($data['year_month']);
            }

            if (isset($data['day'])) {
                $response->assertSee($data['day']);
            }

            if (isset($data['clock_in'])) {
                $response->assertSee($data['clock_in']);
            }

            if (isset($data['clock_out'])) {
                $response->assertSee($data['clock_out']);
            }

            if (isset($data['break_in'])) {
                $response->assertSee($data['break_in']);
            }

            if (isset($data['break_out'])) {
                $response->assertSee($data['break_out']);
            }

            if (isset($data['break_hours'])) {
                $response->assertSee($data['break_hours']);
            }

            if (isset($data['work_hours'])) {
                $response->assertSee($data['work_hours']);
            }

            if (isset($data['user_name'])) {
                $response->assertSee($data['user_name']);
            }
        }

    }

    /**
     * 勤怠情報が表示されていないことを確認するヘルパー関数
     */
    public function assertAttendanceNotDisplayed($response, array $attendanceData): void
    {
        foreach ($attendanceData as $data) {
            if (isset($data['year_month'])) {
                $response->assertDontSee($data['year_month']);
            }

            if (isset($data['day'])) {
                $response->assertDontSee($data['day']);
            }

            if (isset($data['clock_in'])) {
                $response->assertDontSee($data['clock_in']);
            }

            if (isset($data['clock_out'])) {
                $response->assertDontSee($data['clock_out']);
            }

            if (isset($data['break_hours'])) {
                $response->assertDontSee($data['break_hours']);
            }

            if (isset($data['work_hours'])) {
                $response->assertDontSee($data['work_hours']);
            }
        }
    }
}
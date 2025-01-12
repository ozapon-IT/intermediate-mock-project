<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\AttendanceRecordService;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Carbon\Carbon;

class AttendanceRecordServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AttendanceRecordService $attendanceRecordService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->attendanceRecordService = new AttendanceRecordService();
    }

    /**
     * @test
     * AttendanceRecordServiceのcalculateWorkHours()のテスト
     */
    public function testCalculateWorkHours(): void
    {
        $mockedService = $this->partialMock(AttendanceRecordService::class, function ($mock) {
            $mock->shouldReceive('calculateBreakHours')->andReturn(1.00);
        });

        $attendanceRecord = new AttendanceRecord();
        $attendanceRecord->clock_in = Carbon::parse('2025-01-01 09:00:00');
        $attendanceRecord->clock_out = Carbon::parse('2025-01-01 18:00:00');

        $workHours = $mockedService->calculateWorkHours($attendanceRecord);

        $this->assertEquals(8.00, $workHours);
    }

    /**
     * @test
     * AttendanceRecordServiceのcalculateBreakHours()のテスト
     */
    public function testCalculateBreakHours(): void
    {
        $attendanceRecord = AttendanceRecord::factory()->create();

        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
            'break_duration' => 0.5,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
            'break_duration' => 0.5,
        ]);

        $breakHours = $this->attendanceRecordService->calculateBreakHours($attendanceRecord);

        $this->assertEquals(1.00, $breakHours);
    }

    /**
     * @test
     * AttendanceRecordServiceのformatWorkHours()のテスト
     */
    public function testFormatWorkHours(): void
    {
        $mockedService = $this->partialMock(AttendanceRecordService::class, function ($mock) {
            $mock->shouldReceive('calculateBreakHours')->andReturn(1.00);
        });

        $attendanceRecord = new AttendanceRecord();
        $attendanceRecord->clock_in = Carbon::parse('2025-01-01 09:00:00');
        $attendanceRecord->clock_out = Carbon::parse('2025-01-01 18:00:00');

        $formattedWorkHours = $mockedService->formatWorkHours($attendanceRecord);

        $this->assertEquals('8:00', $formattedWorkHours);
    }

    /**
     * @test
     * 退勤時間がない場合のformatWorkHours()のテスト
     */
    public function testFormatWorkHoursWithIncompleteData(): void
    {
        $attendanceRecord = new AttendanceRecord();
        $attendanceRecord->clock_in = Carbon::parse('2025-01-01 09:00:00');
        $attendanceRecord->clock_out = null;
        $formattedWorkHours = $this->attendanceRecordService->formatWorkHours($attendanceRecord);

        $this->assertNull($formattedWorkHours);
    }

    /**
     * @test
     * AttendanceRecordServiceのformatBreakHours()のテスト
     */
    public function testFormatBreakHours(): void
    {
        $attendanceRecord = AttendanceRecord::factory()->create();

        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
            'break_duration' => 0.75,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
            'break_duration' => 0.25,
        ]);

        $formattedBreakHours = $this->attendanceRecordService->formatBreakHours($attendanceRecord);

        $this->assertEquals('1:00', $formattedBreakHours);
    }

    /**
     * @test
     * 休憩終了時間がない場合のformatBreakHours()のテスト
     */
    public function testFormatBreakHoursWithIncompleteData(): void
    {
        $attendanceRecord = AttendanceRecord::factory()->create();

        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
            'break_in' => Carbon::parse('2025-01-01 12:00:00'),
            'break_out' => null,
        ]);

        $formattedBreakHours = $this->attendanceRecordService->formatBreakHours($attendanceRecord);

        $this->assertNull($formattedBreakHours);
    }
}
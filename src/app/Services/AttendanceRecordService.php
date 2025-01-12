<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Carbon\Carbon;

class AttendanceRecordService
{
    /**
     * 勤怠記録の合計労働時間を計算
     *
     * @param AttendanceRecord $attendanceRecord
     * @return float
     */
    public function calculateWorkHours(AttendanceRecord $attendanceRecord): float
    {
        $clockIn = Carbon::parse($attendanceRecord->clock_in);
        $clockOut = Carbon::parse($attendanceRecord->clock_out);

        $totalWorkHours = $clockIn->diffInMinutes($clockOut) / 60;
        $totalBreakHours = $this->calculateBreakHours($attendanceRecord);

        return round($totalWorkHours - $totalBreakHours, 2);
    }

    /**
     * 勤怠記録の合計休憩時間を計算
     *
     * @param AttendanceRecord $attendanceRecord
     * @return float
     */
    public function calculateBreakHours(AttendanceRecord $attendanceRecord): float
    {
        return AttendanceBreak::where('attendance_record_id', $attendanceRecord->id)
            ->sum('break_duration');
    }

    /**
     * 合計労働時間を表示用にフォーマット
     *
     * @param AttendanceRecord $attendanceRecord
     * @return string|null
     */
    public function formatWorkHours(AttendanceRecord $attendanceRecord): ?string
    {
        if (!$attendanceRecord->clock_in || !$attendanceRecord->clock_out) {
            return null;
        }

        $clockIn = Carbon::parse($attendanceRecord->clock_in);
        $clockOut = Carbon::parse($attendanceRecord->clock_out);

        $totalWorkMinutes = $clockIn->diffInMinutes($clockOut);
        $totalBreakMinutes = (int) round($this->calculateBreakHours($attendanceRecord) * 60);

        $actualWorkMinutes = max(0, $totalWorkMinutes - $totalBreakMinutes);
        $hours = floor($actualWorkMinutes / 60);
        $minutes = $actualWorkMinutes % 60;

        return sprintf('%01d:%02d', $hours, $minutes);
    }

    /**
     * 合計休憩時間を表示用にフォーマット
     *
     * @param AttendanceRecord $attendanceRecord
     * @return string|null
     */
    public function formatBreakHours(AttendanceRecord $attendanceRecord): ?string
    {
        $breaks = $attendanceRecord->attendanceBreaks;

        if ($breaks->isEmpty() || $breaks->contains(fn($break) => is_null($break->break_out))) {
            return null;
        }

        $totalBreakMinutes = (int) round($this->calculateBreakHours($attendanceRecord) * 60);
        $hours = floor($totalBreakMinutes / 60);
        $minutes = $totalBreakMinutes % 60;

        return sprintf('%01d:%02d', $hours, $minutes);
    }
}
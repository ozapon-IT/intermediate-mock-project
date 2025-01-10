<?php

namespace App\Services;

use App\Services\AttendanceRecordService;
use App\Models\AttendanceRecord;
use App\Models\AttendanceCorrectRequest;
use Carbon\Carbon;

class AttendanceCorrectionService
{
    protected $attendanceRecordService;

    public function __construct(AttendanceRecordService $attendanceRecordService)
    {
        $this->attendanceRecordService = $attendanceRecordService;
    }

    /**
     * 勤怠修正リクエストを作成
     *
     * @param array $data リクエストデータ（年、月日、出勤時間、退勤時間、休憩時間など）
     * @param AttendanceRecord $attendanceRecord 対象の勤怠記録
     * @return AttendanceCorrectRequest 作成された勤怠修正リクエスト
     */
    public function createAttendanceCorrection(array $data, AttendanceRecord $attendanceRecord)
    {
        $formattedDate = Carbon::createFromFormat('Y年m月d日', "{$data['year']}{$data['month_day']}")->toDateString();

        $attendanceCorrection = AttendanceCorrectRequest::create([
            'attendance_record_id' => $attendanceRecord->id,
            'user_id' => $attendanceRecord->user->id,
            'requested_date' => Carbon::today(),
            'old_date' => $attendanceRecord->date,
            'new_date' => $formattedDate,
            'old_clock_in' => $attendanceRecord->clock_in,
            'old_clock_out' => $attendanceRecord->clock_out,
            'new_clock_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$data['clock_in']}"),
            'new_clock_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$data['clock_out']}"),
            'reason' => $data['reason'],
            'status' => '承認待ち',
        ]);

        if (isset($data['break_in']) && isset($data['break_out'])) {
            foreach ($data['break_in'] as $index => $breakIn) {
                $breakOut = $data['break_out'][$index];

                $oldBreak = $attendanceRecord->attendanceBreaks[$index];

                if ($breakIn && $breakOut) {
                    $attendanceCorrection->breakCorrectRequests()->create([
                        'attendance_break_id' => $oldBreak->id,
                        'old_break_in' => $oldBreak->break_in,
                        'old_break_out' => $oldBreak->break_out,
                        'new_break_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$breakIn}"),
                        'new_break_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$breakOut}"),
                    ]);
                }
            }
        }

        return $attendanceCorrection;
    }

    /**
     * 勤怠記録を修正(更新)
     *
     * @param array $data リクエストデータ（年、月日、出勤時間、退勤時間、休憩時間など）
     * @param AttendanceRecord $attendanceRecord 対象の勤怠記録
     * @return AttendanceRecord 修正された勤怠記録
     */
    public function correctAttendanceRecord(array $data, AttendanceRecord $attendanceRecord)
    {
        $formattedDate = Carbon::createFromFormat('Y年m月d日', "{$data['year']}{$data['month_day']}")->toDateString();

        $attendanceRecord->update([
            'date' => $formattedDate,
            'clock_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$data['clock_in']}"),
            'clock_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$data['clock_out']}"),
        ]);

        if (isset($data['break_in']) && isset($data['break_out'])) {
            foreach ($attendanceRecord->attendanceBreaks as $index => $attendanceBreak) {
                $breakIn = $data['break_in'][$index];
                $breakOut = $data['break_out'][$index];
                $breakDuration = Carbon::parse($breakIn)->diffInMinutes($breakOut) / 60;

                if ($breakIn && $breakOut) {
                    $attendanceBreak->update([
                        'break_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$breakIn}"),
                        'break_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$breakOut}"),
                        'break_duration' => round($breakDuration, 2),
                    ]);
                }
            }
        }

        $attendanceRecord->update([
            'break_hours' => $this->attendanceRecordService->calculateBreakHours($attendanceRecord),
            'work_hours' => $this->attendanceRecordService->calculateWorkHours($attendanceRecord),
            'admin_correction_reason' => $data['reason'],
            'status' => '退勤済',
        ]);

        return $attendanceRecord;
    }

    /**
     * 勤怠修正リクエストを承認し、勤怠記録を更新
     *
     * @param AttendanceCorrectRequest $attendanceCorrectRequest
     * @param array $data リクエストデータ
     * @return void
     */
    public function approveAttendanceCorrection(AttendanceCorrectRequest $attendanceCorrectRequest, array $data)
    {
        // 修正リクエストのステータスを更新
        $attendanceCorrectRequest->update([
            'status' => '承認済み',
        ]);

        $attendanceRecord = $attendanceCorrectRequest->attendanceRecord;
        $attendanceBreaks = $attendanceRecord->attendanceBreaks ?? [];

        $formattedDate = Carbon::createFromFormat('Y年m月d日', "{$data['year']}{$data['month_day']}")->toDateString();

        // 勤怠記録を更新
        $attendanceRecord->update([
            'date' => $formattedDate,
            'clock_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$data['clock_in']}"),
            'clock_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$data['clock_out']}"),
        ]);

        // 休憩時間を更新
        if (isset($data['break_in']) && isset($data['break_out'])) {
            foreach ($attendanceBreaks as $index => $attendanceBreak) {
                $breakIn = $data['break_in'][$index];
                $breakOut = $data['break_out'][$index];

                if (!$breakIn || !$breakOut) {
                    continue;
                }

                $breakDuration = Carbon::parse($breakIn)->diffInMinutes($breakOut) / 60;

                $attendanceBreak->update([
                    'break_in' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$breakIn}"),
                    'break_out' => Carbon::createFromFormat('Y-m-d H:i', "{$formattedDate} {$breakOut}"),
                    'break_duration' => round($breakDuration, 2),
                ]);
            }
        }

        // 労働時間と休憩時間を再計算して更新
        $attendanceRecord->update([
            'break_hours' => $this->attendanceRecordService->calculateBreakHours($attendanceRecord),
            'work_hours' => $this->attendanceRecordService->calculateWorkHours($attendanceRecord),
            'status' => '退勤済',
        ]);
    }
}
<?php

namespace App\Services;

use App\Services\AttendanceRecordService;
use Carbon\Carbon;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;

class AttendanceService
{
    protected $attendanceRecordService;

    public function __construct(AttendanceRecordService $attendanceRecordService)
    {
        $this->attendanceRecordService = $attendanceRecordService;
    }

    /**
     * 本日の勤怠登録画面に表示する情報を取得
     *
     * @param int $userId
     * @return array
     */
    public function getTodayAttendanceInformation(int $userId)
    {
        $now = Carbon::now('Asia/Tokyo');

        $attendanceRecord = AttendanceRecord::where('user_id', $userId)
            ->where('date', Carbon::today())
            ->first();

        $attendanceInformation = [
            'formattedDate' => $now->isoFormat('YYYY年M月D日(ddd)'),
            'formattedTime' => $now->format('H:i'),
            'attendanceRecord' => $attendanceRecord,
            'status' => $attendanceRecord->status ?? '勤務外',
        ];

        return $attendanceInformation;
    }

    /**
     * 勤怠記録の「出勤」処理
     *
     * @param int $userId ユーザーID
     * @return AttendanceRecord 作成された勤怠記録
     */
    public function clockIn(int $userId): AttendanceRecord
    {
        return AttendanceRecord::create([
            'user_id' => $userId,
            'date' => Carbon::today(),
            'clock_in' => Carbon::now()->format('Y-m-d H:i:00'),
            'status' => '出勤中',
        ]);
    }

    /**
     * 勤怠記録の「退勤」処理
     *
     * @param int $userId ユーザーID
     * @return AttendanceRecord 更新された勤怠記録
     */
    public function clockOut(int $userId): AttendanceRecord
    {
        $attendanceRecord = AttendanceRecord::where('user_id', $userId)
            ->whereNull('clock_out')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($attendanceRecord) {
            $attendanceRecord->update([
                'clock_out' => Carbon::now()->format('Y-m-d H:i:00'),
                'break_hours' => $this->attendanceRecordService->calculateBreakHours($attendanceRecord),
                'work_hours' => $this->attendanceRecordService->calculateWorkHours($attendanceRecord),
                'status' => '退勤済',
            ]);
        }

        return $attendanceRecord;
    }

    /**
     * 勤怠記録の休憩開始処理
     *
     * @param int $attendanceRecordId 勤怠記録ID
     * @return AttendanceBreak 作成された休憩記録
     */
    public function breakIn(int $attendanceRecordId): AttendanceBreak
    {
        $break = AttendanceBreak::create([
            'attendance_record_id' => $attendanceRecordId,
            'break_in' => Carbon::now()->format('Y-m-d H:i:00'),
        ]);

        AttendanceRecord::where('id', $attendanceRecordId)->update([
            'status' => '休憩中',
        ]);

        return $break;
    }

    /**
     * 勤怠記録の休憩終了処理
     *
     * @param int $attendanceRecordId 勤怠記録ID
     * @return AttendanceBreak 更新された休憩記録
     */
    public function breakOut(int $attendanceRecordId): AttendanceBreak
    {
        $break = AttendanceBreak::where('attendance_record_id', $attendanceRecordId)
            ->whereNull('break_out')
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $breakOutTime = Carbon::now()->format('Y-m-d H:i:00');

        $breakDuration = Carbon::parse($break->break_in)->diffInMinutes($breakOutTime) / 60;

        $break->update([
            'break_out' => $breakOutTime,
            'break_duration' => round($breakDuration, 2),
        ]);

        AttendanceRecord::where('id', $attendanceRecordId)->update([
            'status' => '出勤中',
        ]);

        return $break;
    }
}
<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\AttendanceRecord;
use App\Models\AttendanceCorrectRequest;

class AttendanceDetailService
{
    /**
     * 指定された勤怠記録を取得してフォーマット
     *
     * @param int $id 勤怠記録のID
     * @param int|null $userId ユーザーID（指定されている場合のみフィルタリング）
     * @return array ['attendanceRecord' => AttendanceRecord, 'breaks' => Collection]
     */
    public function getFormattedAttendanceRecord($id, $userId = null): array
    {
        $query = AttendanceRecord::where('id', $id);
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $attendanceRecord = $query->firstOrFail();

        $attendanceRecord->formatted_year = Carbon::parse($attendanceRecord->date)->format('Y年');
        $attendanceRecord->formatted_month_day = Carbon::parse($attendanceRecord->date)->format('n月j日');
        $attendanceRecord->formatted_clock_in = $attendanceRecord->clock_in ? Carbon::parse($attendanceRecord->clock_in)->format('H:i') : '';
        $attendanceRecord->formatted_clock_out = $attendanceRecord->clock_out ? Carbon::parse($attendanceRecord->clock_out)->format('H:i') : '';

        $breaks = $attendanceRecord->attendanceBreaks;
        foreach ($breaks as $break) {
            $break->formatted_break_in = $break->break_in ? Carbon::parse($break->break_in)->format('H:i') : '';
            $break->formatted_break_out = $break->break_out ? Carbon::parse($break->break_out)->format('H:i') : '';
        }

        return compact('attendanceRecord', 'breaks');
    }

    /**
     * 勤怠修正申請を取得
     *
     * @param int $attendanceRecordId 勤怠記録ID
     * @param int|null $userId ユーザーID（省略時は認証ユーザーIDを使用）
     * @return AttendanceCorrectRequest|null
     */
    public function getAttendanceCorrection(int $attendanceRecordId, ?int $userId = null): ?AttendanceCorrectRequest
    {
        $userId = $userId ?? auth()->id();

        return AttendanceCorrectRequest::where('attendance_record_id', $attendanceRecordId)
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }

    /**
     * 勤怠修正申請をフォーマット
     *
     * @param AttendanceCorrectRequest $attendanceCorrectRequest
     * @return array ['attendanceCorrection' => AttendanceCorrectRequest, 'breakCorrections' => Collection]
     */
    public function formatAttendanceCorrection(AttendanceCorrectRequest $attendanceCorrectRequest): array
    {
        $attendanceCorrection = $attendanceCorrectRequest;
        $attendanceCorrection->formatted_year = $attendanceCorrection->new_date ? Carbon::parse($attendanceCorrection->new_date)->format('Y年') : '';
        $attendanceCorrection->formatted_month_day = $attendanceCorrection->new_date ? Carbon::parse($attendanceCorrection->new_date)->format('n月j日') : '';
        $attendanceCorrection->formatted_new_clock_in = $attendanceCorrection->new_clock_in ? Carbon::parse($attendanceCorrection->new_clock_in)->format('H:i') : '';
        $attendanceCorrection->formatted_new_clock_out = $attendanceCorrection->new_clock_out ? Carbon::parse($attendanceCorrection->new_clock_out)->format('H:i') : '';

        $breakCorrections = $attendanceCorrection->breakCorrectRequests;
        foreach ($breakCorrections as $breakCorrection) {
            $breakCorrection->formatted_new_break_in = $breakCorrection->new_break_in ? Carbon::parse($breakCorrection->new_break_in)->format('H:i') : '';
            $breakCorrection->formatted_new_break_out = $breakCorrection->new_break_out ? Carbon::parse($breakCorrection->new_break_out)->format('H:i') : '';
        }

        return compact('attendanceCorrection', 'breakCorrections');
    }

    /**
     * フォーマットされた勤怠修正申請データを取得
     *
     * @param $attendanceCorrection 勤怠修正申請データ
     * @return array フォーマットされた勤怠修正申請データ
     */
    public function getFormattedCorrectionData($attendanceCorrection): array
    {
        return $attendanceCorrection
            ? $this->formatAttendanceCorrection($attendanceCorrection)
            : ['attendanceCorrection' => null, 'breakCorrections' => []];
    }

    /**
     * 勤怠修正申請が承認待ちかを判定
     *
     * @param AttendanceCorrectRequest|null $attendanceCorrection 勤怠修正申請データ
     * @return bool 勤怠修正申請が承認待ちの場合はtrue、それ以外はfalse
     */
    public function isWaitingApproval($attendanceCorrection): bool
    {
        return $attendanceCorrection && $attendanceCorrection->status === '承認待ち';
    }
}
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
    public function getFormattedAttendanceRecord($id, $userId = null)
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
     * 勤怠修正申請をフォーマット
     *
     * @param AttendanceCorrectRequest $attendanceCorrectRequest
     * @return array ['attendanceCorrection' => AttendanceCorrectRequest, 'breakCorrections' => Collection]
     */
    public function formatAttendanceCorrection(AttendanceCorrectRequest $attendanceCorrectRequest)
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
}
<?php

namespace App\Services;

use App\Models\AttendanceCorrectRequest;
use Illuminate\Support\Carbon;

class RequestListService
{
    /**
     * 勤怠修正申請を取得してフォーマット
     *
     * @param string $status ステータスフィルタ
     * @param int|null $userId ユーザーID（nullの場合は全ユーザー）
     * @return Collection
     */
    public function getFormattedAttendanceCorrections(string $status, ?int $userId = null)
    {
        $query = AttendanceCorrectRequest::where('status', $status);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attendanceCorrections = $query->get();

        foreach ($attendanceCorrections as $attendanceCorrection) {
            $attendanceCorrection->formatted_requested_date = Carbon::parse($attendanceCorrection->requested_date)->isoFormat('YYYY/MM/DD');
            $attendanceCorrection->formatted_old_date = Carbon::parse($attendanceCorrection->old_date)->isoFormat('YYYY/MM/DD');
        }

        return $attendanceCorrections;
    }
}
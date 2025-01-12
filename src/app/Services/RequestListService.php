<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\AttendanceCorrectRequest;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RequestListService
{
    /**
     * クエリパラメータからステータスを取得
     *
     * @param Request $request
     * @param string $default デフォルトのステータス
     * @return string
     */
    public function getStatusFromQuery(Request $request, string $default = '承認待ち'): string
    {
        return $request->query('status', $default);
    }

    /**
     * 勤怠修正申請を取得してフォーマット
     *
     * @param string $status ステータスフィルタ
     * @param int|null $userId ユーザーID（nullの場合は全ユーザー）
     * @return Collection
     */
    public function getFormattedAttendanceCorrections(string $status, ?int $userId = null): Collection
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
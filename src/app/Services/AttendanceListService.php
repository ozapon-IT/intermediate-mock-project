<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\AttendanceRecord;
use App\Services\AttendanceRecordService;
use Illuminate\Database\Eloquent\Collection;

class AttendanceListService
{
    protected $attendanceRecordService;

    public function __construct(AttendanceRecordService $attendanceRecordService)
    {
        $this->attendanceRecordService = $attendanceRecordService;
    }

    /**
     * IDでユーザーを取得
     *
     * @param int $id ユーザーID
     * @return User
     */
    public function getUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * 現在の月を取得
     *
     * @param Request $request
     * @return string
     */
    public function getCurrentMonth(Request $request): string
    {
        return $request->input('month', now()->format('Y-m'));
    }

    /**
     * 月の勤怠記録を取得してフォーマット
     *
     * @param int $userId
     * @param string $currentMonth
     * @return Collection
     */
    public function getFormattedAttendanceRecords(int $userId, string $currentMonth): Collection
    {
        $attendanceRecords = AttendanceRecord::where('user_id', $userId)
            ->whereBetween('date', [
                Carbon::parse($currentMonth)->startOfMonth(),
                Carbon::parse($currentMonth)->endOfMonth(),
            ])
            ->orderBy('date')
            ->get();

        foreach ($attendanceRecords as $record) {
            $record->formatted_date = Carbon::parse($record->date)->locale('ja')->isoFormat('MM/DD(ddd)');
            $record->formatted_clock_in = $record->clock_in ? Carbon::parse($record->clock_in)->format('H:i') : '';
            $record->formatted_clock_out = $record->clock_out ? Carbon::parse($record->clock_out)->format('H:i') : '';
            $record->formatted_break_hours = $this->attendanceRecordService->formatBreakHours($record);
            $record->formatted_work_hours = $this->attendanceRecordService->formatWorkHours($record);
        }

        return $attendanceRecords;
    }

    /**
     * 月のナビゲーションデータを取得
     *
     * @param string $currentMonth
     * @return array
     */
    public function getMonthNavigation(string $currentMonth): array
    {
        return [
            'currentMonthFormatted' => Carbon::parse($currentMonth)->format('Y/m'),
            'previousMonth' => Carbon::parse($currentMonth)->subMonth()->format('Y-m'),
            'nextMonth' => Carbon::parse($currentMonth)->addMonth()->format('Y-m'),
        ];
    }

    /**
     * 現在の日付を取得
     *
     * @param Request $request
     * @return string
     */
    public function getCurrentDay(Request $request): string
    {
        return $request->input('day', now()->format('Y年n月j日'));
    }

    /**
     * 日次の勤怠記録を取得してフォーマット
     *
     * @param string $currentDay 指定日 (Y年n月j日形式)
     * @return Collection
     */
    public function getDailyFormattedAttendanceRecords(string $currentDay): Collection
    {
        $currentDayObj = Carbon::createFromFormat('Y年n月j日', $currentDay);

        $attendanceRecords = AttendanceRecord::with('user')
            ->where('date', $currentDayObj->format('Y-n-j'))
            ->get();

        foreach ($attendanceRecords as $record) {
            $record->formatted_clock_in = $record->clock_in ? Carbon::parse($record->clock_in)->format('H:i') : '';
            $record->formatted_clock_out = $record->clock_out ? Carbon::parse($record->clock_out)->format('H:i') : '';
            $record->formatted_break_hours = $this->attendanceRecordService->formatBreakHours($record);
            $record->formatted_work_hours = $this->attendanceRecordService->formatWorkHours($record);
        }

        return $attendanceRecords;
    }

    /**
     * 日次ナビゲーションデータを取得
     *
     * @param string $currentDay 指定日 (Y年n月j日形式)
     * @return array
     */
    public function getDayNavigation(string $currentDay): array
    {
        $currentDayObj = Carbon::createFromFormat('Y年n月j日', $currentDay);

        return [
            'currentDayFormatted' => $currentDayObj->format('Y/m/d'),
            'previousDay' => $currentDayObj->copy()->subDay()->format('Y年n月j日'),
            'nextDay' => $currentDayObj->copy()->addDay()->format('Y年n月j日'),
        ];
    }

    /**
     * 勤怠記録をCSV形式で出力
     *
     * @param Collection $attendanceRecords フォーマット済みの勤怠記録
     * @return \Closure CSV出力用のコールバック
     */
    public function exportAttendanceRecordsToCsv(Collection $attendanceRecords): \Closure
    {
        $csvHeader = ['日付', '出勤', '退勤', '休憩時間', '合計時間'];

        return function () use ($attendanceRecords, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8
            fputcsv($file, $csvHeader);

            foreach ($attendanceRecords as $record) {
                $row = [
                    $record->formatted_date,
                    $record->formatted_clock_in,
                    $record->formatted_clock_out,
                    $record->formatted_break_hours,
                    $record->formatted_work_hours,
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };
    }
}
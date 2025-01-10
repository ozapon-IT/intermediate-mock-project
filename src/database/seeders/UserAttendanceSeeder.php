<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use App\Models\AttendanceCorrectRequest;
use Carbon\Carbon;

class UserAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ユーザー情報
        $users = [
            ['name' => '西 怜奈', 'email' => 'reina.n@coachtech.com', 'password' => 'testreina'],
            ['name' => '山田 太郎', 'email' => 'taro.y@coachtech.com', 'password' => 'testtaro'],
            ['name' => '増田 一世', 'email' => 'issei.m@coachtech.com', 'password' => 'testissei'],
            ['name' => '山本 敬吉', 'email' => 'keikichi.y@coachtech.com', 'password' => 'testkeikichi'],
            ['name' => '秋田 朋美', 'email' => 'tomomi.a@coachtech.com', 'password' => 'testtomomi'],
            ['name' => '中西 教夫', 'email' => 'norio.n@coachtech.com', 'password' => 'testnorio'],
        ];

        // 各ユーザーの勤怠データを生成
        foreach ($users as $userData) {
            // ユーザー作成
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'email_verified_at' => Carbon::now(),
            ]);

            // 勤怠データ生成
            $startDate = Carbon::parse('2024-11-01');
            $endDate = Carbon::parse('2025-01-10');

            while ($startDate->lte($endDate)) {
                // 勤怠記録作成
                $attendanceRecord = $user->attendanceRecords()->create([
                    'date' => $startDate->copy()->toDateString(),
                    'clock_in' => $startDate->copy()->setTime(9, 0, 0),
                    'clock_out' => $startDate->copy()->setTime(18, 0, 0),
                    'break_hours' => '1.00',
                    'work_hours' => '8.00',
                    'status' => '退勤済',
                ]);

                // 休憩データ作成
                $attendanceRecord->attendanceBreaks()->createMany([
                    [
                        'break_in' => $startDate->copy()->setTime(12, 0, 0),
                        'break_out' => $startDate->copy()->setTime(12, 40, 0),
                        'break_duration' => '0.67'
                    ],
                    [
                        'break_in' => $startDate->copy()->setTime(15, 0, 0),
                        'break_out' => $startDate->copy()->setTime(15, 20, 0),
                        'break_duration' => '0.33',
                    ],
                ]);

                // 次の日へ
                $startDate->addDay();
            }

            // 勤怠修正申請作成
            $correctDate = Carbon::parse('2024-12-31');

            $attendanceRecord = AttendanceRecord::where('date', $correctDate->toDateString())
                ->where('user_id', $user->id)
                ->first();

            $attendanceCorrectRequest = AttendanceCorrectRequest::create([
                'attendance_record_id' => $attendanceRecord->id,
                'user_id' => $user->id,
                'requested_date' => $endDate->copy()->toDateString(),
                'old_date' => $attendanceRecord->date,
                'new_date' => $attendanceRecord->date,
                'old_clock_in' => $attendanceRecord->clock_in,
                'old_clock_out' => $attendanceRecord->clock_out,
                'new_clock_in' => $correctDate->copy()->setTime(10, 0, 0),
                'new_clock_out' => $correctDate->copy()->setTime(19, 0, 0),
                'reason' => 'テスト用修正申請',
                'status' => '承認待ち',
            ]);

            // 休憩修正申請作成
            $breakId1 = AttendanceBreak::where('attendance_record_id', $attendanceRecord->id)
                ->where('break_in', Carbon::parse($attendanceRecord->date)->copy()->setTime(12, 0, 0))
                ->first()->id;

            $breakId2 = AttendanceBreak::where('attendance_record_id', $attendanceRecord->id)
                ->where('break_in', Carbon::parse($attendanceRecord->date)->copy()->setTime(15, 0, 0))
                ->first()->id;

            if ($breakId1 && $breakId2) {
                $attendanceCorrectRequest->breakCorrectRequests()->createMany([
                    [
                        'attendance_break_id' => $breakId1,
                        'old_break_in' => Carbon::parse($attendanceRecord->date)->copy()->setTime(12, 0, 0),
                        'old_break_out' => Carbon::parse($attendanceRecord->date)->copy()->setTime(12, 40, 0),
                        'new_break_in' => $correctDate->copy()->setTime(12, 30, 0),
                        'new_break_out' => $correctDate->copy()->setTime(13, 10, 0),
                    ],
                    [
                        'attendance_break_id' => $breakId2,
                        'old_break_in' => Carbon::parse($attendanceRecord->date)->copy()->setTime(15, 0, 0),
                        'old_break_out' => Carbon::parse($attendanceRecord->date)->copy()->setTime(15, 20, 0),
                        'new_break_in' => $correctDate->copy()->setTime(15, 30, 0),
                        'new_break_out' => $correctDate->copy()->setTime(15, 50, 0),
                    ],
                ]);
            }
        }
    }
}
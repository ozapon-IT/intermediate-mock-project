<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\AttendanceTestHelper;
use App\Services\AttendanceRecordService;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AdminAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    use AttendanceTestHelper;

    /**
     * @test
     * その日になされた全ユーザーの勤怠情報が正確に表示される
     */
    public function it_can_check_correctly_all_users_attendance_information_for_the_day(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'admin');

        Carbon::setTestNow('2025-01-07');
        $users = User::factory()->count(3)->create();

        $attendanceRecords = $users->map(function ($user, $index) {
            return AttendanceRecord::factory()->create([
                'user_id' => $user->id,
                'date' => '2025-01-07',
                'clock_in' => sprintf("2025-01-07 %02d:00:00", 9 + $index),
                'clock_out' => sprintf("2025-01-07 %02d:00:00", 18 + $index),
                'break_hours' => 1.00,
                'work_hours' => 8.00,
            ]);
        });

        // 勤怠一覧画面にアクセス
        $response = $this->get(route('admin.attendance-list.show', ['day' => '2025年1月7日']));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        $service = app(AttendanceRecordService::class);

        // 勤怠情報が正しく表示されていることを確認
        foreach ($attendanceRecords as $record) {
            $response->assertSee($record->user->name);
            $response->assertSee(Carbon::parse($record->clock_in)->format('H:i'));
            $response->assertSee(Carbon::parse($record->clock_out)->format('H:i'));
            $response->assertSee($service->formatBreakHours($record));
            $response->assertSee($service->formatWorkHours($record));
        }
    }

    /**
     * @test
     * 遷移した際に現在の日付が表示される
     */
    public function it_displays_current_date_when_screen_transitioned(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'admin');

        Carbon::setTestNow('2025-01-08');

        $response = $this->get(route('admin.attendance-list.show'));

        $response->assertStatus(200);

        $response->assertSee('2025年1月8日');
    }

    /**
     * @test
     * 「前日」を押下した時に前の日の勤怠情報が表示される
     */
    public function it_displays_previous_day_attendance_information_when_previous_button_clicked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'admin');

        // 前日の勤怠情報の作成
        Carbon::setTestNow('2025-01-07');
        $users = User::factory()->count(2)->create();
        $previousDayRecords = $users->map(function ($user) {
            return $this->createAttendanceRecord($user->id, '2025-01-07', '2025-01-07 09:00:00', '2025-01-07 18:00:00', [
                ['break_in' => '2025-01-07 12:00:00', 'break_out' => '2025-01-07 13:00:00', 'break_duration' => 1.00]
            ]);
        });
        // その日の勤怠情報の作成
        Carbon::setTestNow('2025-01-08');
        $currentDayRecords = $users->map(function ($user) {
            return $this->createAttendanceRecord($user->id, '2025-01-08', '2025-01-08 010:00:00', '2025-01-08 19:00:00', [
                ['break_in' => '2025-01-08 12:30:00', 'break_out' => '2025-01-08 13:20:00', 'break_duration' => 0.84]
            ]);
        });

        // 勤怠一覧画面にアクセスし「前日」ボタンを押す
        $response = $this->get(route('admin.attendance-list.show', ['day' => '2025年1月8日']));
        $response = $this->get(route('admin.attendance-list.show', ['day' => '2025年1月7日']));

        $response->assertStatus(200);

        $user1 = $users[0];
        $user2 = $users[1];

        // 前日の勤怠情報が正しく表示されていることを確認
        $this->assertAttendanceDisplayed($response, [
            ['day' => '2025年1月7日', 'user_name' => $user1->name, 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '1:00', 'work_hours' => '8:00'],
            ['day' => '2025年1月7日', 'user_name' => $user2->name, 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '1:00', 'work_hours' => '8:00'],
        ]);
        // その日の勤怠情報が表示されないことを確認
        $this->assertAttendanceNotDisplayed($response, [
            ['day' => '2025年1月8日', 'user_name' => $user1->name, 'clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:50', 'work_hours' => '8:10'],
            ['day' => '2025年1月8日', 'user_name' => $user2->name, 'clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:50', 'work_hours' => '8:10'],
        ]);
    }

    /**
     * @test
     * 「翌日」を押下した時に次の日の勤怠情報が表示される
     */
    public function it_displays_next_day_attendance_information_when_next_button_clicked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'admin');

        // その日の勤怠情報の作成
        Carbon::setTestNow('2025-01-08');
        $users = User::factory()->count(2)->create();
        $previousDayRecords = $users->map(function ($user) {
            return $this->createAttendanceRecord($user->id, '2025-01-08', '2025-01-08 09:00:00', '2025-01-08 18:00:00', [
                ['break_in' => '2025-01-08 12:00:00', 'break_out' => '2025-01-08 13:00:00', 'break_duration' => 1.00]
            ]);
        });
        // 翌日の勤怠情報の作成
        Carbon::setTestNow('2025-01-09');
        $currentDayRecords = $users->map(function ($user) {
            return $this->createAttendanceRecord($user->id, '2025-01-09', '2025-01-09 010:00:00', '2025-01-09 19:00:00', [
                ['break_in' => '2025-01-09 12:30:00', 'break_out' => '2025-01-09 13:20:00', 'break_duration' => 0.84]
            ]);
        });

        // 勤怠一覧画面にアクセスし「翌日」ボタンを押す
        $response = $this->get(route('admin.attendance-list.show', ['day' => '2025年1月8日']));
        $response = $this->get(route('admin.attendance-list.show', ['day' => '2025年1月9日']));

        $response->assertStatus(200);

        $user1 = $users[0];
        $user2 = $users[1];

        // 翌日の勤怠情報が正しく表示されていることを確認
        $this->assertAttendanceDisplayed($response, [
            ['day' => '2025年1月9日', 'user_name' => $user1->name, 'clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:50', 'work_hours' => '8:10'],
            ['day' => '2025年1月9日', 'user_name' => $user2->name, 'clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:50', 'work_hours' => '8:10'],
        ]);
        // その日の勤怠情報が表示されないことを確認
        $this->assertAttendanceNotDisplayed($response, [
            ['day' => '2025年1月8日', 'user_name' => $user1->name, 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '1:00', 'work_hours' => '8:00'],
            ['day' => '2025年1月8日', 'user_name' => $user2->name, 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '1:00', 'work_hours' => '8:00'],
        ]);
    }
}

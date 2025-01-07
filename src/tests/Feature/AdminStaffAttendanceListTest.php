<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AttendanceTestHelper;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AdminStaffAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    use AttendanceTestHelper;

    /**
     * @test
     * 管理者ユーザーが全一般ユーザーの「氏名」と「メールアドレス」を確認できる
     */
    public function admin_can_view_all_general_users_names_and_emails(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $users = User::factory()->count(3)->create();

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.staff-list.show'));

        $response->assertStatus(200);

        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }

    /**
     * @test
     * ユーザーの勤怠情報が正しく表示される
     */
    public function it_displays_correct_attendance_information_for_selected_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        $user = User::factory()->create();

        // 勤怠情報作成
        $this->createAttendanceRecord($user->id, '2025-01-05', '2025-01-05 09:00:00', '2025-01-05 18:00:00', [
            ['break_in' => '2025-01-05 12:00:00', 'break_out' => '2025-01-05 13:00:00', 'break_duration' => 1.00]
        ]);
        $this->createAttendanceRecord($user->id, '2025-01-06', '2025-01-06 08:30:00', '2025-01-06 17:30:00', [
            ['break_in' => '2025-01-06 11:30:00', 'break_out' => '2025-01-06 12:20', 'break_duration' => 0.84]
        ]);

        // 他のユーザーの勤怠情報作成
        $otherUser = User::factory()->create();
        $this->createAttendanceRecord($otherUser->id, '2025-01-07', '2025-01-07 10:00:00', '2025-01-07 19:00:00', [
            ['break_in' => '2025-01-07 12:30', 'break_out' => '2025-01-07 13:25', 'break_duration' => 0.92]
        ]);

        $response = $this->get(route('admin.staff-attendance-list.show', ['id' => $user->id, 'month' => '2025-01']));

        // 選択したユーザーの勤怠情報が正しく表示されていることを確認
        $response->assertStatus(200);
        $this->assertAttendanceDisplayed($response, [
            ['year_month' => '2025/01', 'day' => '01/05', 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '1:00', 'work_hours' => '8:00'],
            ['day' => '01/06', 'clock_in' => '08:30', 'clock_out' => '17:30', 'break_hours' => '0:50', 'work_hours' => '8:10'],
        ]);

        // 他のユーザーの勤怠情報が表示されていないことを確認
        $this->assertAttendanceNotDisplayed($response, [
            ['day' => '01/07', 'clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:55', 'work_hours' => '8:05'],
        ]);
    }

    /**
     * @test
     * 「前月」を押下した時に表示月の前月の情報が表示される
     */
    public function it_displays_previous_month_information_when_previous_month_button_is_pressed(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        $user = User::factory()->create();

        // 前月の勤怠情報を作成
        $this->createAttendanceRecord($user->id, '2024-12-07', '2024-12-07 09:00:00', '2024-12-07 18:00:00', [
            ['break_in' => '2024-12-07 12:00:00', 'break_out' => '2024-12-07 13:00:00', 'break_duration' => 1.00]
        ]);
        $this->createAttendanceRecord($user->id, '2024-12-08', '2024-12-08 08:30:00', '2024-12-08 17:30:00', [
            ['break_in' => '2024-12-08 11:30:00', 'break_out' => '2024-12-08 12:20', 'break_duration' => 0.84]
        ]);

        // 今月の勤怠情報を作成
        $this->createAttendanceRecord($user->id, '2025-01-06', '2025-01-06 10:00:00', '2025-01-06 19:00:00', [
            ['break_in' => '2025-01-06 12:30:00', 'break_out' => '2025-01-06 13:25', 'break_duration' => 0.92]
        ]);

        $response = $this->get(route('admin.staff-attendance-list.show', ['id' => $user->id, 'month' => '2024-12']));

        // 前月の情報が表示されていることを確認
        $response->assertStatus(200);
        $this->assertAttendanceDisplayed($response, [
            ['year_month' => '2024/12', 'day' => '12/07', 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '1:00', 'work_hours' => '8:00'],
            ['day' => '12/08', 'clock_in' => '08:30', 'clock_out' => '17:30', 'break_hours' => '0:50', 'work_hours' => '8:10'],
        ]);

        // 今月の情報が表示されていないことを確認
        $this->assertAttendanceNotDisplayed($response, [
            ['year_month' => '2025/01', 'day' => '01/06', 'clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:55', 'work_hours' => '8:05'],
        ]);
    }

    /**
     * @test
     * 「翌月」を押下した時に表示月の翌月の情報が表示される
     */
    public function it_displays_next_month_information_when_next_month_button_is_pressed(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        $user = User::factory()->create();

        // 今月の勤怠情報を作成
        $this->createAttendanceRecord($user->id, '2025-01-07', '2025-01-07 10:00:00', '2025-01-07 19:00:00', [
            ['break_in' => '2025-01-07 12:30:00', 'break_out' => '2025-01-07 13:25:00', 'break_duration' => 0.92]
        ]);

        // 翌月の勤怠情報を作成
        $this->createAttendanceRecord($user->id, '2025-02-06', '2025-02-06 09:00:00', '2025-02-06 18:00:00', [
            ['break_in' => '2025-02-06 12:00:00', 'break_out' => '2025-02-06 13:00:00', 'break_duration' => 1.00]
        ]);
        $this->createAttendanceRecord($user->id, '2025-02-08', '2025-02-08 08:30:00', '2025-02-08 17:30:00', [
            ['break_in' => '2025-02-08 11:30:00', 'break_out' => '2025-02-08 12:20', 'break_duration' => 0.84]
        ]);

        $response = $this->get(route('admin.staff-attendance-list.show', ['id' => $user->id, 'month' => '2025-02']));

        // 翌月の情報が表示されていることを確認
        $response->assertStatus(200);
        $this->assertAttendanceDisplayed($response, [
            ['year_month' => '2025/02', 'day' => '02/06', 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '1:00', 'work_hours' => '8:00'],
            ['day' => '02/08', 'clock_in' => '08:30', 'clock_out' => '17:30', 'break_hours' => '0:50', 'work_hours' => '8:10'],
        ]);

        // 今月の情報が表示されていないことを確認
        $this->assertAttendanceNotDisplayed($response, [
            ['year_month' => '2025/01', 'day' => '01/07', 'clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:55', 'work_hours' => '8:05'],
        ]);
    }

    /**
     * @test
     * 「詳細」を押下するとその日の勤怠詳細画面に遷移する
     */
    public function it_navigates_attendance_detail_page_when_detail_button_is_pressed(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-07 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('admin.staff-attendance-list.show', ['id' => $user->id, 'month' => '2025-01']));
        $response->assertStatus(200);

        $response = $this->get(route('admin.attendance-detail.show', $attendanceRecord->id));
        $response->assertStatus(200);
        $response->assertSee('2025年');
        $response->assertSee('1月7日');
    }
}

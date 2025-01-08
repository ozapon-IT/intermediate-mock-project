<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AttendanceTestHelper;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;
    use AttendanceTestHelper;

    /**
     * @test
     * 自分が行った勤怠情報が全て表示されている
     */
    public function it_displays_all_attendance_records_for_the_logged_in_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // ログインユーザーの勤怠情報を生成
        $this->createAttendanceRecord($user->id, '2025-01-01', '2025-01-01 09:00:00', '2025-01-01 18:00:00', [
            ['break_in' => '2025-01-01 12:00:00', 'break_out' => '2025-01-01 12:45:00', 'break_duration' => 0.75]
        ]);
        $this->createAttendanceRecord($user->id, '2025-01-02', '2025-01-02 09:30:00', '2025-01-02 18:30:00', [
            ['break_in' => '2025-01-02 12:10:00', 'break_out' => '2025-01-02 13:00:00', 'break_duration' => 0.84]
        ]);

        // 他のユーザーの勤怠情報を生成（表示されないことを確認する）
        $otherUser = User::factory()->create();
        $this->createAttendanceRecord($otherUser->id, '2025-01-02', '2025-01-02 10:00:00', '2025-01-02 19:00:00', [
            ['break_in' => '2025-01-02 12:30', 'break_out' => '2025-01-02 13:25', 'break_duration' => 0.92]
        ]);

        $response = $this->get(route('attendance-list.show', ['month' => '2025-01']));

        // 自分の勤怠情報が全て表示されていることを確認
        $response->assertStatus(200);
        $this->assertAttendanceDisplayed($response, [
            ['year_month' => '2025/01', 'day' => '01/01', 'clock_in' => '09:00', 'clock_out' => '18:00', 'break_hours' => '0:45', 'work_hours' => '8:15'],
            ['day' => '01/02', 'clock_in' => '09:30', 'clock_out' => '18:30', 'break_hours' => '0:50', 'work_hours' => '8:10'],
        ]);

        // 他のユーザーの勤怠情報が表示されていないことを確認
        $this->assertAttendanceNotDisplayed($response, [
            ['clock_in' => '10:00', 'clock_out' => '19:00', 'break_hours' => '0:55', 'work_hours' => '8:05'],
        ]);
    }

    /**
     * @test
     * 勤怠一覧画面に遷移した際に現在の月が表示される
     */
    public function it_displays_current_month_on_attendance_list_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05');

        $response = $this->get(route('attendance-list.show'));

        $response->assertStatus(200);
        $response->assertSee('2025/01');
    }

    /**
     * @test
     * 「前月」を押下した時に表示月の前月の情報が表示される
     */
    public function it_displays_previous_month_information_when_previous_month_button_is_pressed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

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

        // 勤怠一覧画面で「前月」を押下
        $response = $this->get(route('attendance-list.show', ['month' => '2024-12']));

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
        $user = User::factory()->create();
        $this->actingAs($user);

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

        // 勤怠一覧画面で「翌月」を押下
        $response = $this->get(route('attendance-list.show', ['month' => '2025-02']));

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
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('attendance-list.show'));
        $response->assertStatus(200);

        $response = $this->get(route('attendance-detail.show', $attendanceRecord->id));
        $response->assertStatus(200);
        $response->assertSee('2025年');
        $response->assertSee('1月5日');
    }
}

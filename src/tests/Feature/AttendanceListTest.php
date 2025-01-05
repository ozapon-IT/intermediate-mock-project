<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Illuminate\Support\Carbon;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 自分が行った勤怠情報が全て表示されている
     */
    public function it_displays_all_attendance_records_for_the_logged_in_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-01');
        $attendanceRecord1 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'clock_in' => '2025-01-01 09:00:00',
            'clock_out' => '2025-01-01 18:00:00',
        ]);

        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord1->id,
            'break_in' => '2025-01-01 12:00:00',
            'break_out' => '2025-01-01 12:45:00',
            'break_duration' => 0.75,
        ]);

        Carbon::setTestNow('2025-01-02');
        $attendanceRecord2 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'clock_in' => '2025-01-02 09:30:00',
            'clock_out' => '2025-01-02 18:30:00',
        ]);

        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord2->id,
            'break_in' => '2025-01-02 12:10:00',
            'break_out' => '2025-01-02 13:00:00',
            'break_duration' => 0.84,
        ]);

        // 他のユーザーの勤怠情報を生成（表示されないことを確認する）
        $attendanceRecord3 = AttendanceRecord::factory()->create([
            'user_id' => User::factory()->create()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'clock_in' => '2025-01-02 10:00:00',
            'clock_out' => '2025-01-02 19:00:00',
        ]);

        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord3->id,
            'break_in' => '2025-01-02 12:30:00',
            'break_out' => '2025-01-02 13:25:00',
            'break_duration' => 0.92,
        ]);

        $response = $this->get(route('attendance-list.show', ['month' => '2025-01']));

        // 自分の勤怠情報が全て表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('01/01');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('0:45');
        $response->assertSee('8:15');
        $response->assertSee('01/02');
        $response->assertSee('09:30');
        $response->assertSee('18:30');
        $response->assertSee('0:50');
        $response->assertSee('8:10');

        // 他のユーザーの勤怠情報が表示されていないことを確認
        $response->assertDontSee('10:00');
        $response->assertDontSee('19:00');
        $response->assertDontSee('0:55');
        $response->assertDontSee('8:05');
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
        Carbon::setTestNow('2024-12-05 09:00:00');
        $attendanceRecord1 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord1->id,
        ]);

        // 今月の勤怠情報を作成
        Carbon::setTestNow('2025-01-05 08:30:00');
        $attendanceRecord2 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord2->id,
            'break_in' => '2025-01-05 12:00:00',
            'break_out' => '2025-01-05 12:45:00',
            'break_duration' => 0.75,
        ]);

        // 勤怠一覧画面で「前月」を押下
        $response = $this->get(route('attendance-list.show', ['month' => '2024-12']));

        // 前月の情報が表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('2024/12');
        $response->assertSee('12/05');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('1:00');
        $response->assertSee('8:00');

        // 今月の情報が表示されていないことを確認
        $response->assertDontSee('2025/01');
        $response->assertDontSee('01/05');
        $response->assertDontSee('08:30');
        $response->assertDontSee('17:30');
        $response->assertDontSee('0:45');
        $response->assertDontSee('8:15');
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
        Carbon::setTestNow('2025-01-05 09:00:00');
        $attendanceRecord1 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord1->id,
        ]);

        // 翌月の勤怠情報を作成
        Carbon::setTestNow('2025-02-05 08:30:00');
        $attendanceRecord2 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord2->id,
            'break_in' => '2025-02-05 12:30:00',
            'break_out' => '2025-02-05 13:20:00',
            'break_duration' => 0.84,
        ]);

        // 勤怠一覧画面で「翌月」を押下
        $response = $this->get(route('attendance-list.show', ['month' => '2025-02']));

        // 翌月の情報が表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('2025/02');
        $response->assertSee('02/05');
        $response->assertSee('08:30');
        $response->assertSee('17:30');
        $response->assertSee('0:50');
        $response->assertSee('8:10');

        // 今月の情報が表示されていないことを確認
        $response->assertDontSee('2025/01');
        $response->assertDontSee('01/05');
        $response->assertDontSee('09:00');
        $response->assertDontSee('18:00');
        $response->assertDontSee('1:00');
        $response->assertDontSee('8:00');
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
        Carbon::setTestNow('2025-01-05 12:00:00');
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $response = $this->get(route('attendance-list.show'));

        // 「詳細」ボタンを押す
        $response = $this->get(route('attendance-detail.show', $attendanceRecord->id));

        //  勤怠詳細画面に遷移し、正しい情報が表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('2025年');
        $response->assertSee('1月5日');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('12:00');
        $response->assertSee('13:00');
    }
}

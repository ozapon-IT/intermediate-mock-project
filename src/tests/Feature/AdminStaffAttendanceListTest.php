<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Illuminate\Support\Carbon;

class AdminStaffAttendanceListTest extends TestCase
{
    use RefreshDatabase;

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

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        // 他のユーザーの勤怠情報を生成（表示されないことを確認する）
        Carbon::setTestNow('2025-01-07 09:00:00');
        $otherAttendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => User::factory()->create()->id,
            'clock_in' => '2025-01-07 10:00:00',
            'clock_out' => '2025-01-07 19:00:00',
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $otherAttendanceRecord->id,
            'break_in' => '2025-01-02 12:30:00',
            'break_out' => '2025-01-02 13:25:00',
            'break_duration' => 0.92,
        ]);

        $response = $this->get(route('admin.staff-attendance-list.show', ['id' => $user->id]));

        // 選択したユーザーの勤怠情報が全て表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('2025/01');
        $response->assertSee('01/06');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('1:00');
        $response->assertSee('8:00');

        // 他のユーザーの勤怠情報が表示されていないことを確認
        $response->assertDontSee('01/07');
        $response->assertDontSee('10:00');
        $response->assertDontSee('19:00');
        $response->assertDontSee('0:55');
        $response->assertDontSee('8:05');
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
        Carbon::setTestNow('2024-12-07 09:00:00');
        $attendanceRecord1 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord1->id,
        ]);

        // 今月の勤怠情報を作成
        Carbon::setTestNow('2025-01-07 08:30:00');
        $attendanceRecord2 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord2->id,
            'break_in' => '2025-01-07 12:00:00',
            'break_out' => '2025-01-07 12:45:00',
            'break_duration' => 0.75,
        ]);

        $response = $this->get(route('admin.staff-attendance-list.show', ['id' => $user->id, 'month' => '2024-12']));

        // 前月の情報が表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('2024/12');
        $response->assertSee('12/07');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('1:00');
        $response->assertSee('8:00');

        // 今月の情報が表示されていないことを確認
        $response->assertDontSee('2025/01');
        $response->assertDontSee('01/07');
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
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        $user = User::factory()->create();

        // 今月の勤怠情報を作成
        Carbon::setTestNow('2024-01-07 09:00:00');
        $attendanceRecord1 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord1->id,
        ]);

        // 翌月の勤怠情報を作成
        Carbon::setTestNow('2025-02-07 08:30:00');
        $attendanceRecord2 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord2->id,
            'break_in' => '2025-02-07 12:30:00',
            'break_out' => '2025-02-07 13:20:00',
            'break_duration' => 0.84,
        ]);

        $response = $this->get(route('admin.staff-attendance-list.show', ['id' => $user->id, 'month' => '2025-02']));

        // 翌月の情報が表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('2025/02');
        $response->assertSee('02/07');
        $response->assertSee('08:30');
        $response->assertSee('17:30');
        $response->assertSee('0:50');
        $response->assertSee('8:10');

        // 今月の情報が表示されていないことを確認
        $response->assertDontSee('2025/01');
        $response->assertDontSee('01/07');
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

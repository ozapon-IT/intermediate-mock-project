<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AttendanceClockOutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 退勤ボタンが正しく機能する
     */
    public function it_works_clock_out_button_properly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05 09:00:00');

        $attendanceRecord = AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'clock_in' => Carbon::now()->format('Y-m-d H:i'),
            'status' => '出勤中',
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertStatus(200);
        $response->assertSee('退勤');

        Carbon::setTestNow('2025-01-05 18:00:00');
        $response = $this->post(route('attendance.clock_out'));
        $response->assertRedirect(route('attendance.show'));

        $this->assertDatabaseHas('attendance_records', [
            'id' => $attendanceRecord->id,
            'status' => '退勤済',
            'clock_out' => '2025-01-05 18:00:00',
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertStatus(200);
        $response->assertSee('退勤済');
    }

    /**
     * @test
     * 退勤時刻が管理画面で確認できる
     */
    public function it_can_check_clock_out_time_on_admin_screen(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05 09:00:00');
        $this->post(route('attendance.clock_in'));

        Carbon::setTestNow('2025-01-05 18:00:00');
        $this->post(route('attendance.clock_out'));

        $response = $this->actingAs($admin, 'admin')->get(route('admin.attendance-list.show', [
            'day' => '2025年1月5日'
        ]));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('18:00');
    }
}

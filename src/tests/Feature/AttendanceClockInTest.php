<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class AttendanceClockInTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 出勤ボタンが正しく機能する
     */
    public function it_works_clock_in_button_properly()
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-04 08:00:00');

        $response = $this->actingAs($user)->get(route('attendance.show'));

        $response->assertStatus(200);

        $response->assertSeeText('出勤');

        $clockInResponse = $this->actingAs($user)->post(route('attendance.clock_in'));

        $clockInResponse->assertRedirect(route('attendance.show'));

        $updatedResponse = $this->actingAs($user)->get(route('attendance.show'));

        $updatedResponse->assertSeeText('出勤中');

        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'status' => '出勤中',
        ]);
    }

    /**
     * @test
     * 出勤は一日一回のみできる
     */
    public function it_can_clock_in_only_once_day()
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-04 18:00:00');

        AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'clock_in' => Carbon::now()->subHours(9)->format('Y-m-d H:i:'),
            'clock_out' => Carbon::now()->format('Y-m-d H:i'),
            'status' => '退勤済',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.show'));

        $response->assertStatus(200);

        $response->assertDontSee('出勤');
    }

    /**
     * @test
     * 出勤時刻が管理画面で確認できる
     */
    public function it_can_check_clock_in_time_on_admin_screen()
    {
        $user = User::factory()->create();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Carbon::setTestNow('2025-01-04 08:00:00');

        $this->actingAs($user)->post(route('attendance.clock_in'));

        $response = $this->actingAs($admin, 'admin')->get(route('admin.attendance-list.show', [
            'day' => Carbon::today()->format('Y年n月j日'),
        ]));

        $response->assertStatus(200);

        $response->assertSee($user->name);
        $response->assertSee(Carbon::now()->format('H:i'));
    }
}

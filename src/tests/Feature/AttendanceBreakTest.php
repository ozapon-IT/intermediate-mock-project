<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class AttendanceBreakTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 休憩入ボタンが正しく機能する
     */
    public function it_works_break_in_button_properly() : void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05 10:00:00');

        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertStatus(200);
        $response->assertSee('休憩入');

        $response = $this->post(route('attendance.break_in'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);
        $response->assertRedirect(route('attendance.show'));

        $this->assertDatabaseHas('attendance_records', [
            'id' => $attendanceRecord->id,
            'status' => '休憩中',
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertStatus(200);
        $response->assertSee('休憩中');
    }

    /**
     * @test
     * 休憩入は一日に何回でもできる
     */
    public function it_allows_multiple_break_in_a_day(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05 10:00:00');

        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
        ]);

        $this->post(route('attendance.break_in'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $this->post(route('attendance.break_out'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertSee('休憩入');
    }

    /**
     * @test
     * 休憩戻ボタンが正しく機能する
     */
    public function it_works_break_out_button_properly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05 10:00:00');

        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
        ]);

        $this->post(route('attendance.break_in'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertSee('休憩戻');

        $response = $this->post(route('attendance.break_out'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);
        $response->assertRedirect(route('attendance.show'));

        $this->assertDatabaseHas('attendance_records', [
            'id' => $attendanceRecord->id,
            'status' => '出勤中',
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertSee('出勤中');
    }

    /**
     * @test
     * 休憩戻は一日に何回でもできる
     */
    public function it_allows_multiple_break_out_a_day(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-05 10:00:00');

        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
        ]);

        $this->post(route('attendance.break_in'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $this->post(route('attendance.break_out'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $this->post(route('attendance.break_in'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $response = $this->get(route('attendance.show'));
        $response->assertStatus(200);
        $response->assertSee('休憩戻');
    }

    /**
     * @test
     * 休憩時刻が管理画面で確認できる
     */
    public function it_can_check_break_times_on_admin_screen(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        Carbon::setTestNow('2025-01-05 09:00:00');

        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
        ]);

        Carbon::setTestNow('2025-01-05 12:00:00');
        $this->post(route('attendance.break_in'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        Carbon::setTestNow('2025-01-05 13:00:00');
        $this->post(route('attendance.break_out'), [
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.attendance-list.show', [
            'day' => Carbon::today()->format('Y年n月j日'),
        ]));

        $response->assertStatus(200);
        $response->assertSee('2025年1月5日');
        $response->assertSee($user->name);
        $response->assertSee('1:00');
    }
}
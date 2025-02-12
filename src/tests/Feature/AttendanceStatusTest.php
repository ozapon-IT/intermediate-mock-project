<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use App\Models\AttendanceBreak;

class AttendanceStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 勤務外の場合、勤怠ステータスが正しく表示される
     */
    public function it_displays_correct_status_when_user_is_off_duty() : void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-04 10:30:00');

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '勤務外',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.show'));

        $response->assertStatus(200);

        $response->assertSeeText('勤務外');
    }

    /**
     * @test
     * 出勤中の場合、勤怠ステータスが正しく表示される
     */
    public function it_displays_correct_status_when_user_is_clocked_in() : void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-04 10:30:00');

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '出勤中',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.show'));

        $response->assertSeeText('出勤中');
    }

    /**
     * @test
     * 休憩中の場合、勤怠ステータスが正しく表示される
     */
    public function it_displays_correct_status_when_user_is_on_break() : void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-04 10:30:00');

        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '休憩中',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.show'));

        $response->assertSeeText('休憩中');
    }

    /**
     * @test
     * 退勤済の場合、勤怠ステータスが正しく表示される
     */
    public function it_displays_correct_status_when_user_is_clocked_out() : void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-04 18:00:00');

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'status' => '退勤済',
        ]);

        $response = $this->actingAs($user)->get(route('attendance.show'));

        $response->assertSeeText('退勤済');
    }
}

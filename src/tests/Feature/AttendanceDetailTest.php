<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Illuminate\Support\Carbon;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 勤怠詳細画面の「名前」がログインユーザーの氏名になっている
     */
    public function it_displays_logged_in_user_name_on_attendance_detail_page(): void
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('attendance-detail.show', $attendanceRecord->id));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
    }

    /**
     * @test
     * 勤怠詳細画面の「日付」が選択した日付になっている
     */
    public function it_displays_selected_date_on_attendance_detail_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('attendance-detail.show', $attendanceRecord->id));

        $response->assertSee('2025年');
        $response->assertSee('1月6日');
    }

    /**
     * @test
     *「出勤・退勤」に記されている時間がログインユーザーの打刻と一致している
     */
    public function it_displays_correct_clock_in_and_clock_out_times_on_attendance_detail_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('attendance-detail.show', $attendanceRecord->id));

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    /**
     * @test
     *「休憩」に記されている時間がログインユーザーの打刻と一致している
     */
    public function it_displays_correct_break_times_on_attendance_detail_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $response = $this->get(route('attendance-detail.show', $attendanceRecord->id));

        $response->assertSee('12:00');
        $response->assertSee('13:00');
    }
}
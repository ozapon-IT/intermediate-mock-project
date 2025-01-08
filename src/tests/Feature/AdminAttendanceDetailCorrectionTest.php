<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AttendanceTestHelper;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Illuminate\Support\Carbon;

class AdminAttendanceDetailCorrectionTest extends TestCase
{
    use RefreshDatabase;
    use AttendanceTestHelper;

    /**
     * @test
     * 勤怠詳細画面に表示されるデータが選択したものになっている
     */
    public function it_displays_selected_attendance_data_on_admin_detail_page(): void
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

        $response = $this->get(route('admin.attendance-detail.show', $attendanceRecord->id));

        $response->assertStatus(200);
        $response->assertSee('2025年');
        $response->assertSee('1月6日');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('12:00');
        $response->assertSee('13:00');
    }

    /**
     * @test
     * 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
     */
    public function it_shows_validation_message_when_clock_in_is_after_clock_out(): void
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

        $this->get(route('admin.attendance-detail.show', $attendanceRecord->id));

        $response = $this->patch(route('admin.attendance-detail.correct', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '19:00',
            'clock_out' => '18:00',
            'reason' => 'テスト修正リクエスト',
        ]);

        $response->assertSessionHasErrors([
            'clock_in' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
        $response->assertRedirect(route('admin.attendance-detail.show', $attendanceRecord->id));

        $followed = $this->followRedirects($response);
        $followed->assertSee('出勤時間もしくは退勤時間が不適切な値です');
    }

    /**
     * @test
     * 休憩開始時間が出勤時間より前になっている場合、エラーメッセージが表示される
     */
    public function it_shows_validation_message_when_break_in_is_before_clock_in(): void
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
        Carbon::setTestNow('2025-01-06 12:00:00');
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $this->get(route('admin.attendance-detail.show', $attendanceRecord->id));

        $response = $this->patch(route('admin.attendance-detail.correct', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_in' => ['0' => '08:30'],
            'break_out' => ['0' => '10:00'],
            'reason' => 'テスト修正リクエスト',
        ]);

        $response->assertSessionHasErrors([
            'break_in.0' => '休憩時間が勤務時間外です',
        ]);

        $followed = $this->followRedirects($response);
        $followed->assertSee('休憩時間が勤務時間外です');
    }

    /**
     * @test
     * 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
     */
    public function it_shows_validation_message_when_break_out_is_after_clock_out(): void
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
        Carbon::setTestNow('2025-01-06 12:00:00');
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $this->get(route('admin.attendance-detail.show', $attendanceRecord->id));

        $response = $this->patch(route('admin.attendance-detail.correct', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_in' => ['0' => '12:00'],
            'break_out' => ['0' => '19:00'],
            'reason' => 'テスト修正リクエスト',
        ]);

        $response->assertSessionHasErrors([
            'break_out.0' => '休憩時間が勤務時間外です',
        ]);

        $followed = $this->followRedirects($response);
        $followed->assertSee('休憩時間が勤務時間外です');
    }

    /**
     * @test
     * 備考欄が未入力の場合、エラーメッセージが表示される
     */
    public function it_shows_validation_message_when_reason_is_missing(): void
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

        $this->get(route('admin.attendance-detail.show', $attendanceRecord->id));

        $response = $this->patch(route('admin.attendance-detail.correct', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'reason' => '',
        ]);

        $response->assertSessionHasErrors([
            'reason' => '備考を記入してください',
        ]);

        $followed = $this->followRedirects($response);
        $followed->assertSee('備考を記入してください');
    }
}

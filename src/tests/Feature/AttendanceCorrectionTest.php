<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\AttendanceBreak;
use Illuminate\Support\Carbon;
use App\Models\AttendanceCorrectRequest;

class AttendanceCorrectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
     */
    public function it_shows_validation_message_when_clock_in_is_after_clock_out(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(route('attendance-detail.request_correction', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '19:00',
            'clock_out' => '18:00',
            'reason' => 'テスト用の修正リクエスト',
        ]);

        $response->assertSessionHasErrors([
            'clock_in' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
        $response->assertRedirect(route('attendance-detail.show', $attendanceRecord->id));

        $followed = $this->followRedirects($response);
        $followed->assertSee('出勤時間もしくは退勤時間が不適切な値です');
    }

    /**
     * @test
     * 休憩開始時間が出勤時間より前になっている場合、エラーメッセージが表示される
     */
    public function it_shows_validation_message_when_break_in_is_before_clock_in(): void
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

        $response = $this->post(route('attendance-detail.request_correction', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_in' => ['0' => '08:30'],
            'break_out' => ['0' => '10:00'],
            'reason' => 'テスト用の修正リクエスト',
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
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceBreak::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
        ]);

        $response = $this->post(route('attendance-detail.request_correction', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_in' => ['0' => '12:00'],
            'break_out' => ['0' => '19:00'],
            'reason' => 'テスト用の修正リクエスト',
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
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(route('attendance-detail.request_correction', $attendanceRecord->id), [
            'year'       => '2025年',
            'month_day'  => '1月6日',
            'clock_in'   => '09:00',
            'clock_out'  => '18:00',
            'reason'     => '',
        ]);

        $response->assertSessionHasErrors([
            'reason' => '備考を記入してください',
        ]);

        $followed = $this->followRedirects($response);
        $followed->assertSee('備考を記入してください');
    }

    /**
     * @test
     * 修正申請処理が実行される
     */
    public function it_executes_correction_request_processing(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(route('attendance-detail.request_correction', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月7日',
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'reason' => 'テスト用の修正申請',
        ]);

        $response->assertRedirect(route('request-list.show'));

        $this->assertDatabaseHas('attendance_correct_requests', [
            'attendance_record_id' => $attendanceRecord->id,
            'user_id' => $user->id,
            'new_date' => '2025-01-07',
            'new_clock_in' => '2025-01-07 10:00:00',
            'new_clock_out' => '2025-01-07 19:00:00',
            'reason' => 'テスト用の修正申請',
            'status' => '承認待ち',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        $requestListResponse = $this->get(route('admin.request-list.show', ['status' => '承認待ち']));
        $requestListResponse->assertOk();
        $requestListResponse->assertSee($user->name);
        $requestListResponse->assertSee('テスト用の修正申請');

        $attendanceCorrection = AttendanceCorrectRequest::first();
        $approvalResponse = $this->get(route('admin.approve-request.show', ['attendance_correct_request' => $attendanceCorrection->id]));
        $approvalResponse->assertOk();
        $approvalResponse->assertSee($user->name);
        $approvalResponse->assertSee('テスト用の修正申請');
        $approvalResponse->assertSee('承認');
    }

    /**
     * @test
     *「承認待ち」にログインユーザーが行った申請が全て表示されていること
     */
    public function it_displays_all_requests_for_the_logged_in_user_on_waiting_approval_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // ログインユーザーの勤怠情報を作成
        Carbon::setTestNow('2025-01-04 09:00:00');
        $attendanceRecord1 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        Carbon::setTestNow('2025-01-05 09:00:00');
        $attendanceRecord2 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        // 他のユーザーの勤怠情報&修正申請を作成（表示されないことを確認）
        $otherUser = User::factory()->create();
        Carbon::setTestNow('2025-01-06 09:00:00');
        $otherAttendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $otherUser->id,
        ]);
        AttendanceCorrectRequest::factory()->create([
            'attendance_record_id' => $otherAttendanceRecord->id,
            'user_id' => $otherUser->id,
            'status' => '承認待ち',
            'reason' => '他のユーザーの申請',
        ]);

        // ログインユーザーの勤怠詳細を修正し保存処理
        $this->post(route('attendance-detail.request_correction', $attendanceRecord1->id), [
            'year' => '2025年',
            'month_day' => '1月4日',
            'clock_in' => '08:30',
            'clock_out' => '17:30',
            'reason' => 'テスト申請1',
        ]);

        $this->post(route('attendance-detail.request_correction', $attendanceRecord2->id), [
            'year' => '2025年',
            'month_day' => '1月5日',
            'clock_in' => '09:30',
            'clock_out' => '18:30',
            'reason' => 'テスト申請2',
        ]);

        $response = $this->get(route('request-list.show', ['status' => '承認待ち']));

        $response->assertOk();

        // ログインユーザーの申請が表示されていることを確認
        $response->assertSee('テスト申請1');
        $response->assertSee('テスト申請2');

        // 他のユーザーの申請が表示されていないことを確認
        $response->assertDontSee('他のユーザーの申請');
    }

    /**
     * @test
     * 「承認済み」に管理者が承認した修正申請が全て表示されている
     */
    public function it_displays_all_requests_approved_by_administrator_on_approved_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-04 09:00:00');
        $attendanceRecord1 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceCorrectRequest::factory()->create([
            'attendance_record_id' => $attendanceRecord1->id,
            'user_id' => $user->id,
            'status' => '承認済み',
        ]);
        Carbon::setTestNow('2025-01-05 09:00:00');
        $attendanceRecord2 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceCorrectRequest::factory()->create([
            'attendance_record_id' => $attendanceRecord2->id,
            'user_id' => $user->id,
            'status' => '承認済み',
        ]);

        // 承認待ちの修正申請を作成（表示されないことを確認）
        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord3 = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);
        AttendanceCorrectRequest::factory()->create([
            'attendance_record_id' => $attendanceRecord3->id,
            'user_id' => $user->id,
            'status' => '承認待ち',
        ]);

        $response = $this->get(route('request-list.show', ['status' => '承認済み']));

        $response->assertStatus(200);

        // 承認済みの申請情報が表示されていることを確認
        $response->assertSee('2025/01/04');
        $response->assertSee('2025/01/05');

        // 承認待ちの申請情報が表示されていないことを確認
        $response->assertDontSee('2025/01/06');
    }

    /**
     * @test
     * 各申請の「詳細」を押下すると申請詳細画面に遷移する
     */
    public function it_navigates_to_request_detail_page_when_detail_link_is_clicked(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Carbon::setTestNow('2025-01-06 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->post(route('attendance-detail.request_correction', $attendanceRecord->id), [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '08:30',
            'clock_out' => '17:30',
            'reason' => 'テスト修正申請',
        ]);

        $response = $this->get(route('request-list.show', ['status' => '承認待ち']));

        // 「詳細」リンクが存在することを確認
        $response->assertSee(route('attendance-detail.show', $attendanceRecord->id));

        $detailResponse = $this->get(route('attendance-detail.show', $attendanceRecord->id));

        $detailResponse->assertStatus(200);

        $detailResponse->assertSee('テスト修正申請');
        $detailResponse->assertSee('*承認待ちのため修正はできません。');
    }
}

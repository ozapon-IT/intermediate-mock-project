<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\AttendanceCorrectRequest;
use Carbon\Carbon;

class AdminApproveRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 承認待ちの修正申請が全て表示されている
     */
    public function it_displays_all_pending_correction_requests(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        Carbon::setTestNow('2025-01-07 09:00:00');
        $pendingRequests = AttendanceCorrectRequest::factory()->count(3)->create();
        $approvedRequests = AttendanceCorrectRequest::factory()->count(3)->create([
            'status' => '承認済み',
        ]);

        $response = $this->get(route('admin.request-list.show', ['status' => '承認待ち']));
        $response->assertStatus(200);

        // 承認待ちの修正申請が表示されているか確認
        foreach ($pendingRequests as $request) {
            $response->assertSee($request->user->name);
            $response->assertSee(Carbon::parse($request->old_date)->isoFormat('YYYY/MM/DD'));
            $response->assertSee($request->reason);
            $response->assertSee(Carbon::parse($request->requested_date)->isoFormat('YYYY/MM/DD'));
        }

        // 承認済みの修正申請が表示されていないことを確認
        foreach ($approvedRequests as $request) {
            $response->assertDontSee($request->user->name);
        }
    }

    /**
     * @test
     * 承認済みの修正申請が全て表示されている
     */
    public function it_displays_all_approved_correction_requests(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        Carbon::setTestNow('2025-01-07 09:00:00');
        $pendingRequests = AttendanceCorrectRequest::factory()->count(3)->create();
        $approvedRequests = AttendanceCorrectRequest::factory()->count(3)->create([
            'status' => '承認済み',
        ]);

        $response = $this->get(route('admin.request-list.show', ['status' => '承認済み']));
        $response->assertStatus(200);

        // 承認済みの修正申請が表示されているか確認
        foreach ($approvedRequests as $request) {
            $response->assertSee($request->user->name);
            $response->assertSee(Carbon::parse($request->old_date)->isoFormat('YYYY/MM/DD'));
            $response->assertSee($request->reason);
            $response->assertSee(Carbon::parse($request->requested_date)->isoFormat('YYYY/MM/DD'));
        }

        // 承認待ちの修正申請が表示されていないことを確認
        foreach ($pendingRequests as $request) {
            $response->assertDontSee($request->user->name);
        }
    }

    /**
     * @test
     * 修正申請の詳細内容が正しく表示されている
     */
    public function it_displays_correction_request_details_correctly(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        Carbon::setTestNow('2025-01-07 09:00:00');
        $attendanceCorrection = AttendanceCorrectRequest::factory()->create([
            'reason' => 'テスト修正申請内容表示',
        ]);

        $response = $this->get(route('admin.approve-request.show', $attendanceCorrection));
        $response->assertStatus(200);

        $response->assertSee('2024年');
        $response->assertSee('12月31日');
        $response->assertSee('10:00');
        $response->assertSee('19:00');
        $response->assertSee('テスト修正申請内容表示');
    }

    /**
     * @test
     * 修正申請の承認処理が正しく行われる
     */
    public function it_processes_correction_request_approval_correctly(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($admin, 'admin');

        Carbon::setTestNow('2025-01-07 09:00:00');
        $attendanceRecord = AttendanceRecord::factory()->create([
        ]);

        $attendanceCorrection = AttendanceCorrectRequest::factory()->create([
            'attendance_record_id' => $attendanceRecord->id,
            'new_date' => '2025-01-06',
            'new_clock_in' => '2025-01-06 10:00:00',
            'new_clock_out' => '2025-01-06 19:00:00',
            'reason' => 'テスト修正申請承認',
        ]);

        $postData = [
            'year' => '2025年',
            'month_day' => '1月6日',
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'reason' => 'テスト修正申請承認',
        ];

        $response = $this->post(route('admin.approve-request.approve', $attendanceCorrection), $postData);
        $response->assertRedirect(route('admin.request-list.show', ['status' => '承認済み']));

        $this->assertDatabaseHas('attendance_correct_requests', [
            'id' => $attendanceCorrection->id,
            'status' => '承認済み',
        ]);

        $this->assertDatabaseHas('attendance_records', [
            'id' => $attendanceRecord->id,
            'date' => '2025-01-06',
            'clock_in' => '2025-01-06 10:00:00',
            'clock_out' => '2025-01-06 19:00:00',
        ]);
    }

}

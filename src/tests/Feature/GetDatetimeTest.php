<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class GetDatetimeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 現在の日時情報がUIと同じ形式で出力されている
     */
    public function it_outputs_current_date_and_time_in_same_format_as_ui(): void
    {
        $user = User::factory()->create();

        Carbon::setTestNow('2025-01-04 10:30:00');

        $response = $this->actingAs($user)->get(route('attendance.show'));

        $response->assertStatus(200);

        $expectedDate = Carbon::now('Asia/Tokyo')->isoFormat('YYYY年M月D日(ddd)');
        $expectedTime = Carbon::now('Asia/Tokyo')->format('H:i');

        $response->assertSeeText($expectedDate);
        $response->assertSeeText($expectedTime);
    }
}

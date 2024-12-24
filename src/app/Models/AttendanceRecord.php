<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\AttendanceBreak;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'break_hours',
        'work_hours',
        'status',
    ];

    public function calculateWorkHours()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        $clockIn = Carbon::parse($this->clock_in);
        $clockOut = Carbon::parse($this->clock_out);

        $totalWorkMinutes = $clockIn->diffInMinutes($clockOut);

        $totalBreakMinutes = $this->calculateBreakMinutes();

        $actualWorkMinutes = max(0, $totalWorkMinutes - $totalBreakMinutes);

        $hours = floor($actualWorkMinutes / 60);
        $minutes = $actualWorkMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function calculateBreakMinutes()
    {
        $totalBreakHours = AttendanceBreak::where('attendance_record_id', $this->id)->sum('break_duration');

        $totalBreakMinutes = (int) round($totalBreakHours * 60);

        return $totalBreakMinutes;
    }
}

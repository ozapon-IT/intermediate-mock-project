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
        $clockIn = Carbon::parse($this->clock_in);
        $clockOut = Carbon::parse($this->clock_out);

        $totalWorkHours = round($clockIn->diffInMinutes($clockOut) / 60, 2);

        $actualWorkHours = $totalWorkHours - $this->calculateBreakHours();

        return $actualWorkHours;
    }

    public function calculateBreakHours()
    {
        $totalBreakHours = AttendanceBreak::where('attendance_record_id', $this->id)->sum('break_duration');

        return $totalBreakHours;
    }
}

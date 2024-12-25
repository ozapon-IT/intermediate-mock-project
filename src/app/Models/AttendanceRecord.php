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

    public function breaks()
    {
        return $this->hasMany(AttendanceBreak::class, 'attendance_record_id');
    }

    public function calculateWorkHours()
    {
        $clockIn = Carbon::parse($this->clock_in);
        $clockOut = Carbon::parse($this->clock_out);

        $totalWorkHours = $clockIn->diffInMinutes($clockOut) / 60;
        $totalBreakHours = $this->calculateBreakHours();

        $actualWorkHours = $totalWorkHours - $totalBreakHours;

        return round($actualWorkHours, 2);
    }

    public function calculateBreakHours()
    {
        $totalBreakHours = AttendanceBreak::where('attendance_record_id', $this->id)
            ->sum('break_duration');

        return $totalBreakHours;
    }

    public function calculateBreakMinutes()
    {
       $totalBreakMinutes = (int) round($this->calculateBreakHours() * 60);

        return $totalBreakMinutes;
    }

    public function formatWorkHours()
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

    public function formatBreakHours()
    {
        $breaks =$this->breaks;

        if ($breaks->isEmpty()) {
            return null;
        }

        $totalBreakMinutes = $this->calculateBreakMinutes();
        $hours = floor($totalBreakMinutes / 60);
        $minutes = $totalBreakMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceBreaks()
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

    public function formatWorkHours()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        $clockIn = Carbon::parse($this->clock_in);
        $clockOut = Carbon::parse($this->clock_out);

        $totalWorkMinutes = $clockIn->diffInMinutes($clockOut);

        $totalBreakMinutes = (int) round($this->calculateBreakHours() * 60);

        $actualWorkMinutes = max(0, $totalWorkMinutes - $totalBreakMinutes);

        $hours = floor($actualWorkMinutes / 60);
        $minutes = $actualWorkMinutes % 60;

        return sprintf('%01d:%02d', $hours, $minutes);
    }

    public function formatBreakHours()
    {
        $breaks =$this->attendanceBreaks;

        if ($breaks->isEmpty()) {
            return null;
        }

        if ($breaks->contains(function ($break) {
            return is_null($break->break_out);
        })) {
            return null;
        }

        $totalBreakMinutes = (int) round($this->calculateBreakHours() * 60);
        $hours = floor($totalBreakMinutes / 60);
        $minutes = $totalBreakMinutes % 60;

        return sprintf('%01d:%02d', $hours, $minutes);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Carbon\Carbon;
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
        'admin_correction_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceBreaks()
    {
        return $this->hasMany(AttendanceBreak::class, 'attendance_record_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCorrectRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_record_id',
        'user_id',
        'requested_date',
        'old_date',
        'new_date',
        'old_clock_in',
        'old_clock_out',
        'new_clock_in',
        'new_clock_out',
        'reason',
        'status',
    ];

    public function breakCorrectRequests()
    {
        return $this->hasMany(BreakCorrectRequest::class, 'attendance_correct_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakCorrectRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_correct_request_id',
        'attendance_break_id',
        'old_break_in',
        'old_break_out',
        'new_break_in',
        'new_break_out',
    ];

    public function attendanceCorrectRequest()
    {
        return $this->belongsTo(AttendanceCorrectRequest::class, 'attendance_correct_request_id');
    }
}

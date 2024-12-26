<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
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

    public function breakCorrections()
    {
        return $this->hasMany(BreakCorrection::class, 'attendance_correction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

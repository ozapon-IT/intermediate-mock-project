<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * 勤怠修正申請とのリレーションを定義
     *
     * @return BelongsTo
     */
    public function attendanceCorrectRequest(): BelongsTo
    {
        return $this->belongsTo(AttendanceCorrectRequest::class, 'attendance_correct_request_id');
    }
}
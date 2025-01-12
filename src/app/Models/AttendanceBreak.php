<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_record_id',
        'break_in',
        'break_out',
        'break_duration',
    ];

    /**
     * 勤怠記録とのリレーションを定義
     *
     * @return BelongsTo
     */
    public function attendanceRecord(): BelongsTo
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * ユーザーとのリレーションを定義
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 休憩記録とのリレーションを定義
     *
     * @return HasMany
     */
    public function attendanceBreaks(): HasMany
    {
        return $this->hasMany(AttendanceBreak::class, 'attendance_record_id');
    }
}

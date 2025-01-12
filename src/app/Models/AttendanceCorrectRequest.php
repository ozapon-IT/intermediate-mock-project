<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * ユーザーとのリレーションを定義
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 勤怠記録とのリレーションを定義
     *
     * @return BelongsTo
     */
    public function attendanceRecord(): BelongsTo
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id');
    }

    /**
     * 休憩修正申請とのリレーションを定義
     *
     * @return HasMany
     */
    public function breakCorrectRequests(): HasMany
    {
        return $this->hasMany(BreakCorrectRequest::class, 'attendance_correct_request_id');
    }
}

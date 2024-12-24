<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_correction_id',
        'break_id',
        'old_break_in',
        'old_break_out',
        'new_break_in',
        'new_break_out',
    ];
}

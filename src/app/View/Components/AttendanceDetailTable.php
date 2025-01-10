<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AttendanceDetailTable extends Component
{
    public $mode;
    public $attendanceRecord;
    public $attendanceCorrection;
    public $breaks;
    public $breakCorrections;
    public $isWaitingApproval;

    /**
     * Create a new component instance.
     *
     * @param string $mode テーブルの表示モード（user, admin, approval）
     * @param mixed $attendanceRecord 勤怠記録
     * @param mixed $attendanceCorrection 勤怠修正
     * @param array $breaks 休憩データ
     * @param array $breakCorrections 修正された休憩データ
     * @param bool $isWaitingApproval 承認待ちかどうか
     */
    public function __construct(
        $mode,
        $attendanceRecord = null,
        $attendanceCorrection = null,
        $breaks = [],
        $breakCorrections = [],
        $isWaitingApproval = false
    )
    {
        $this->mode = $mode;
        $this->attendanceRecord = $attendanceRecord;
        $this->attendanceCorrection = $attendanceCorrection;
        $this->breaks = $breaks;
        $this->breakCorrections = $breakCorrections;
        $this->isWaitingApproval = $isWaitingApproval;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.attendance-detail-table');
    }
}

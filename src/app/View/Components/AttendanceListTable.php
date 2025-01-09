<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AttendanceListTable extends Component
{
    public $attendanceRecords;
    public $columns;
    public $fields;
    public $detailRoute;

    /**
     * Create a new component instance.
     *
     * @param mixed $attendanceRecords 勤怠記録のコレクション
     * @param array $columns テーブルの列名
     * @param array|callable[] $fields テーブルの各列のデータ取得方法（プロパティ名またはクロージャ）
     * @param callable $detailRoute 各レコードの詳細ページへのリンクを生成するコールバック
     */
    public function __construct($attendanceRecords, $columns, $fields, $detailRoute)
    {
        $this->attendanceRecords = $attendanceRecords;
        $this->columns = $columns;
        $this->fields = $fields;
        $this->detailRoute = $detailRoute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.attendance-list-table');
    }
}

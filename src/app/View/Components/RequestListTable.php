<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RequestListTable extends Component
{
    public $attendanceCorrections;
    public $columns;
    public $fields;
    public $detailRoute;

    /**
     * Create a new component instance.
     *
     * @param mixed $attendanceCorrections 申請データのコレクション
     * @param array $columns テーブルの列名
     * @param array|callable[] $fields 各列のデータ取得方法（プロパティ名またはクロージャ）
     * @param callable $detailRoute 各申請の詳細ページリンクを生成するコールバック
     */
    public function __construct($attendanceCorrections, $columns, $fields, $detailRoute)
    {
        $this->attendanceCorrections = $attendanceCorrections;
        $this->columns = $columns;
        $this->fields = $fields;
        $this->detailRoute = $detailRoute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.request-list-table');
    }
}

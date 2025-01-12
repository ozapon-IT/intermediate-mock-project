<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tabs extends Component
{
    public $status;
    public $routeName;
    public $tabs;

    /**
     * Create a new component instance.
     *
     * @param string $status 現在のタブの状態
     * @param string $routeName ルート名
     * @param array $tabs タブ情報（キー: 表示名, 値: ステータス値）
     */
    public function __construct($status, $routeName, $tabs)
    {
        $this->status = $status;
        $this->routeName = $routeName;
        $this->tabs = $tabs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tabs');
    }
}
<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navigation extends Component
{
    public $previousLink;
    public $currentLabel;
    public $nextLink;
    public $type;

    /**
     * Create a new component instance.
     *
     * @param string $previousLink 前のリンクのURL
     * @param string $currentLabel 現在の表示ラベル
     * @param string $nextLink 次のリンクのURL
     * @param string $type ナビゲーションタイプ (month, day)
     */
    public function __construct($previousLink, $currentLabel, $nextLink, $type = 'month')
    {
        $this->previousLink = $previousLink;
        $this->currentLabel = $currentLabel;
        $this->nextLink = $nextLink;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navigation');
    }
}

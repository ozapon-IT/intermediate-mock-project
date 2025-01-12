<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AttendanceDetailButton extends Component
{
    public $type;
    public $isWaitingApproval;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param bool $isWaitingApproval
     */
    public function __construct($type, $isWaitingApproval)
    {
        $this->type = $type;
        $this->isWaitingApproval = $isWaitingApproval;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.attendance-detail-button');
    }
}

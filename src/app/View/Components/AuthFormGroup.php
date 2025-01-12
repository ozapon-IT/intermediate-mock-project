<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthFormGroup extends Component
{
    public $auth;
    public $label;
    public $type;
    public $id;
    public $name;
    public $value;

    /**
     * Create a new component instance.
     *
     * @param string $auth 対象の認証画面（例: "register" や "login"）
     * @param string $label 入力フィールドのラベル名
     * @param string $type 入力フィールドのタイプ（例: "text", "password"）
     * @param string $id 入力フィールドのID
     * @param string $name 入力フィールドのname属性
     * @param string|null $value 入力フィールドの初期値（省略可能）
     */
    public function __construct(
        $auth,
        $label,
        $type,
        $id,
        $name,
        $value = null
    )
    {
        $this->auth = $auth;
        $this->label = $label;
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.auth-form-group');
    }
}

<div class="{{ $auth }}__form-group">
    <label class="{{ $auth }}__label" for="{{ $id }}">{{ $label }}</label>

    <input
        class="{{ $auth }}__input"
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value ?? '' }}"
        {{ $attributes }}
    >

    <x-validation-error :field="$name" />
</div>
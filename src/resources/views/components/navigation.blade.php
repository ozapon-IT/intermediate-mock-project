<div class="attendance-list__{{ $type }}">
    <a class="attendance-list__previous-{{ $type }}" href="{{ $previousLink }}">
        <i class="bi bi-arrow-left-short"></i> {{ $type === 'day' ? '前日' : '前月' }}
    </a>

    <p class="attendance-list__calendar">
        <i class="bi bi-calendar3"></i> {{ $currentLabel }}
    </p>

    <a class="attendance-list__next-{{ $type }}" href="{{ $nextLink }}">
        {{ $type === 'day' ? '翌日' : '翌月' }} <i class="bi bi-arrow-right-short"></i>
    </a>
</div>
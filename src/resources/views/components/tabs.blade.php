<div class="request-list__tabs">
    @foreach ($tabs as $label => $value)
        <a class="request-list__tab {{ $status === $value ? 'request-list__tab--active' : '' }}" href="{{ route($routeName, ['status' => $value]) }}">
            {{ $label }}
        </a>
    @endforeach
</div>
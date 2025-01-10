<table class="request-list__records">
    <tr class="request-list__item">
        @foreach ($columns as $column)
            <th>{{ $column }}</th>
        @endforeach
    </tr>

    @foreach ($attendanceCorrections as $attendanceCorrection)
        <tr class="request-list__item">
            @foreach ($fields as $field)
                <td>
                    @if (is_callable($field))
                        {{ $field($attendanceCorrection) }}
                    @else
                        {{ $attendanceCorrection->{$field} }}
                    @endif
                </td>
            @endforeach
            <td><a href="{{ $detailRoute($attendanceCorrection) }}">詳細</a></td>
        </tr>
    @endforeach
</table>
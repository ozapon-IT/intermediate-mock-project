<table class="attendance-list__records">
    <tr class="attendance-list__item">
        @foreach ($columns as $column)
            <th>{{ $column }}</th>
        @endforeach
    </tr>

    @foreach ($attendanceRecords as $record)
        <tr class="attendance-list__item">
            @foreach ($fields as $field)
                <td>
                    @if (is_callable($field))
                        {{ $field($record) }}
                    @else
                        {{ $record->{$field} }}
                    @endif
                </td>
            @endforeach
            <td><a href="{{ $detailRoute($record) }}">詳細</a></td>
        </tr>
    @endforeach
</table>
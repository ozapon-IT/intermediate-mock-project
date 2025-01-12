<table class="attendance-detail__records">
    <tr class="attendance-detail__item attendance-detail__name">
        <th>名前</th>

        <th>
            @if ($mode === 'approval')
                {{ $attendanceCorrection->user->name }}
            @elseif ($mode === 'admin')
                {{ $attendanceRecord->user->name }}
            @else
                {{ Auth::user()->name }}
            @endif
        </th>
    </tr>

    <tr class="attendance-detail__item attendance-detail__date">
        <td>日付</td>

        <td>
            @if ($mode === 'approval')
                <p>{{ $attendanceCorrection->formatted_year }}</p>

                <p>{{ $attendanceCorrection->formatted_month_day }}</p>

                <input type="hidden" name="year" value="{{ $attendanceCorrection->formatted_year }}">

                <input type="hidden" name="month_day" value="{{ $attendanceCorrection->formatted_month_day }}">
            @else
                @if ($isWaitingApproval && $attendanceCorrection)
                    <p>{{ $attendanceCorrection->formatted_year }}</p>

                    <p>{{ $attendanceCorrection->formatted_month_day }}</p>
                @else
                    <div>
                        <input type="text" name="year" value="{{ old('year', $attendanceRecord->formatted_year) }}">

                        <input type="text" name="month_day" value="{{ old('month_day', $attendanceRecord->formatted_month_day) }}">

                        <x-validation-error field="year" />

                        <x-validation-error field="month_day" />
                    </div>
                @endif
            @endif
        </td>
    </tr>

    <tr class="attendance-detail__item attendance-detail__working-time">
        <td>出勤・退勤</td>

        <td>
            @if ($mode === 'approval')
                <p>{{ $attendanceCorrection->formatted_new_clock_in }}</p>

                <span>〜</span>

                <p>{{ $attendanceCorrection->formatted_new_clock_out }}</p>

                <input type="hidden" name="clock_in" value="{{ $attendanceCorrection->formatted_new_clock_in }}">

                <input type="hidden" name="clock_out" value="{{ $attendanceCorrection->formatted_new_clock_out }}">
            @else
                @if ($isWaitingApproval && $attendanceCorrection)
                    <p>{{ $attendanceCorrection->formatted_new_clock_in }}</p>

                    <span>〜</span>

                    <p>{{ $attendanceCorrection->formatted_new_clock_out }}</p>
                @else
                    <div>
                        <input type="text" name="clock_in" value="{{ old('clock_in', $attendanceRecord->formatted_clock_in) }}">

                        <span>〜</span>

                        <input type="text" name="clock_out" value="{{ old('clock_out', $attendanceRecord->formatted_clock_out ?? '') }}">

                        <x-validation-error field="clock_in" />

                        <x-validation-error field="clock_out" />
                    </div>
                @endif
            @endif
        </td>
    </tr>

    @if ($mode === 'approval')
        @foreach ($breakCorrections as $index => $breakCorrection)
            <tr class="attendance-detail__item attendance-detail__break">
                <td>{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</td>

                <td>
                    <p>{{ $breakCorrection->formatted_new_break_in }}</p>

                    <span>〜</span>

                    <p>{{ $breakCorrection->formatted_new_break_out }}</p>

                    <input type="hidden" name="break_in[{{ $index }}]" value="{{ $breakCorrection->formatted_new_break_in }}">

                    <input type="hidden" name="break_out[{{ $index }}]" value="{{ $breakCorrection->formatted_new_break_out }}">
                </td>
            </tr>
        @endforeach
    @else
        @if ($isWaitingApproval && $breakCorrections)
            @foreach ($breakCorrections as $index => $breakCorrection)
                <tr class="attendance-detail__item attendance-detail__break">
                    <td>{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</td>

                    <td>
                        <p>{{ $breakCorrection->formatted_new_break_in }}</p>

                        <span>〜</span>

                        <p>{{ $breakCorrection->formatted_new_break_out }}</p>
                    </td>
                </tr>
            @endforeach
        @else
            @foreach ($breaks as $index => $break)
                <tr class="attendance-detail__item attendance-detail__break">
                    <td>{{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}</td>

                    <td>
                        <div>
                            <input type="text" name="break_in[{{ $index }}]" value="{{ old('break_in.'.$index, $break->formatted_break_in) }}">

                            <span>〜</span>

                            <input type="text" name="break_out[{{ $index }}]" value="{{ old('break_out.'.$index, $break->formatted_break_out ?? '') }}">

                            <x-validation-error :field="'break_in.'.$index" />

                            <x-validation-error :field="'break_out.'.$index" />
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    @endif

    <tr class="attendance-detail__item attendance-detail__reason">
        <td>備考</td>

        <td>
            @if ($mode === 'approval')
                <p>{{ $attendanceCorrection->reason }}</p>

                <input type="hidden" name="reason" value="{{ $attendanceCorrection->reason }}">
            @else
                @if ($isWaitingApproval && $attendanceCorrection)
                    <p>{{ $attendanceCorrection->reason }}</p>
                @else
                    <div>
                        <textarea name="reason">{{ old('reason') }}</textarea>

                        <x-validation-error field="reason" />
                    </div>
                @endif
            @endif
        </td>
    </tr>
</table>
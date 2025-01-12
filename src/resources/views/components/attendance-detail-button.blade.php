@if ($isWaitingApproval ?? false)
    @if ($type === 'correction')
        <p>*承認待ちのため修正はできません。</p>
    @elseif ($type === 'approval')
        <button class="attendance-detail__button attendance-detail__button--approve" type="submit">
            承認
        </button>
    @endif
@else
    @if ($type === 'correction')
        <button class="attendance-detail__button" type="submit">
            修正
        </button>
    @elseif ($type === 'approval')
        <button class="attendance-detail__button attendance-detail__button--approved">
            承認済み
        </button>
    @endif
@endif
<header class="header">
    <div class="header__container">
        <img class="header__logo" src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">

        @if ($type === 'default')
            <!-- 何も追加しないシンプルなヘッダー -->

        @elseif ($type === 'user')
            <!-- 一般ユーザー用ヘッダー -->
            <nav class="header__nav">
                <a class="header__link" href="{{ route('attendance.show') }}">勤怠</a>

                <a class="header__link" href="{{ route('attendance-list.show') }}">勤怠一覧</a>

                <a class="header__link" href="{{ route('request-list.show') }}">申請</a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="header__button" type="submit">ログアウト</button>
                </form>
            </nav>

        @elseif ($type === 'admin')
            <!-- 管理者用ヘッダー -->
            <nav class="header__nav">
                <a class="header__link" href="{{ route('admin.attendance-list.show') }}">勤怠一覧</a>

                <a class="header__link" href="{{ route('admin.staff-list.show') }}">スタッフ一覧</a>

                <a class="header__link" href="{{ route('admin.request-list.show') }}">申請一覧</a>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button class="header__button" type="submit">ログアウト</button>
                </form>
            </nav>
        @endif
    </div>
</header>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申請一覧画面（一般ユーザー） - COACHTECH勤怠管理</title>
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/request-list.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="/">
                <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
            </a>

            <nav class="header__nav">
                <a class="header__link" href="/attendance" class="header__link">勤怠</a>

                <a class="header__link" href="/attendance/list" class="header__link">勤怠一覧</a>

                <a class="header__link" href="/stamp_correction_request/list" class="header__link">申請</a>

                <form action="/" method="GET">
                    <button class="header__button" type="submit">ログアウト</button>
                </form>
            </nav>
        </div>
    </header>

    <main>
        <div class="request-list">
            <h1 class="request-list__title">申請一覧</h1>

            <div class="request-list__tabs">
                <p class="request-list__tab request-list__tab--waiting">承認待ち</p>
                <p class="request-list__tab request-list__tab--done">承認済み</p>
            </div>

            <table class="request-list__records">
                <tr class="request-list__item">
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>

                <tr class="request-list__item">
                    <td>承認待ち</td>
                    <td>西怜奈</td>
                    <td>2024/12/01</td>
                    <td>遅延のため</td>
                    <td>2024/12/02</td>
                    <td><a href="/attendance/{id}">詳細</a></td>
                </tr>

                <tr class="request-list__item">
                    <td>承認待ち</td>
                    <td>西怜奈</td>
                    <td>2024/12/01</td>
                    <td>遅延のため</td>
                    <td>2024/12/02</td>
                    <td><a href="/attendance/{id}">詳細</a></td>
                </tr>

                <tr class="request-list__item">
                    <td>承認待ち</td>
                    <td>西怜奈</td>
                    <td>2024/12/01</td>
                    <td>遅延のため</td>
                    <td>2024/12/02</td>
                    <td><a href="/attendance/{id}">詳細</a></td>
                </tr>

                <tr class="request-list__item">
                    <td>承認待ち</td>
                    <td>西怜奈</td>
                    <td>2024/12/01</td>
                    <td>遅延のため</td>
                    <td>2024/12/02</td>
                    <td><a href="/attendance/{id}">詳細</a></td>
                </tr>

                <tr class="request-list__item">
                    <td>承認待ち</td>
                    <td>西怜奈</td>
                    <td>2024/12/01</td>
                    <td>遅延のため</td>
                    <td>2024/12/02</td>
                    <td><a href="/attendance/{id}">詳細</a></td>
                </tr>

                <tr class="request-list__item">
                    <td>承認待ち</td>
                    <td>西怜奈</td>
                    <td>2024/12/01</td>
                    <td>遅延のため</td>
                    <td>2024/12/02</td>
                    <td><a href="/request/{id}">詳細</a></td>
                </tr>

                <tr class="request-list__item">
                    <td>承認待ち</td>
                    <td>西怜奈</td>
                    <td>2024/12/01</td>
                    <td>遅延のため</td>
                    <td>2024/12/02</td>
                    <td><a href="/attendance/{id}">詳細</a></td>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>
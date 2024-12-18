<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠詳細画面（一般ユーザー） - COACHTECH勤怠管理</title>
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
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
        <div class="attendance-detail">
            <h1 class="attendance-detail__title">勤怠詳細</h1>

            <table class="attendance-detail__records">
                <tr class="attendance-detail__item attendance-detail__name">
                    <th>名前</th>
                    <th>西 怜奈</th>
                </tr>

                <tr class="attendance-detail__item attendance-detail__date">
                    <td>日付</td>
                    <td>
                        <input type="text" value="2024年">
                        <input type="text" value="12月1日">
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__working-time">
                    <td>出勤・退勤</td>
                    <td>
                        <input type="text" value="09:00">
                        <span>~</span>
                        <input type="text" value="18:00">
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__break">
                    <td>休憩</td>
                    <td>
                        <input type="text" value="12:00">
                        <span>~</span>
                        <input type="text" value="13:00">
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__reason">
                    <td>備考</td>
                    <td>
                        <textarea name="" id="">電車遅延のため</textarea>
                    </td>
                </tr>
            </table>

            <div class="attendance-detail__correction">
                <form action="#">
                    <button class="attendance-detail__button" type="submit">修正</button>
                </form>
            </div>

            <table class="attendance-detail__records">
                <tr class="attendance-detail__item attendance-detail__name">
                    <th>名前</th>
                    <th>西 怜奈</th>
                </tr>

                <tr class="attendance-detail__item attendance-detail__date">
                    <td>日付</td>
                    <td>
                        <p>2024年</p>
                        <p>12月1日</p>
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__working-time">
                    <td>出勤・退勤</td>
                    <td>
                        <p>09:00</p>
                        <span>~</span>
                        <p>18:00</p>
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__break">
                    <td>休憩</td>
                    <td>
                        <p>12:00</p>
                        <span>~</span>
                        <p>13:00</p>
                    </td>
                </tr>

                <tr class="attendance-detail__item attendance-detail__reason">
                    <td>備考</td>
                    <td>
                        <p>電車遅延のため</p>
                    </td>
                </tr>
            </table>

            <div class="attendance-detail__correction">
                <p>*承認待ちの為修正はできません。</p>
            </div>
        </div>
    </main>
</body>

</html>
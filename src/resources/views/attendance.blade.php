<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠登録画面（一般ユーザー） - COACHTECH勤怠管理</title>
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="/">
                <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
            </a>

            <nav class="header__nav">
                <a class="header__link" href="#" class="header__link">勤怠</a>

                <a class="header__link" href="#" class="header__link">勤怠一覧</a>

                <a class="header__link" href="#" class="header__link">申請</a>

                <button class="header__button" type="submit">ログアウト</button>
            </nav>
        </div>
    </header>

    <main>
        <div class="attendance">
            <form class="attendance__form" action="#">
                <span class="attendance__status">勤務外</span>

                <p class="attendance__date">2024年12月16日(月)</p>

                <p class="attendance__time">08:00</p>

                <button class="attendance__button attendance__working">出勤</button>

                <div class="attendance__buttons">
                    <button class="attendance__button attendance__leaving">退勤</button>

                    <button class="attendance__button attendance__break--begins">休憩入</button>
                </div>

                <button class="attendance__button attendance__break--ends">休憩戻</button>

                <p class="attendance__message">お疲れ様でした。</p>
            </form>
        </div>
    </main>
</body>

</html>
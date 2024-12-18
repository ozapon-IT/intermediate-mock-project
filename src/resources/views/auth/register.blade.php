<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録画面（一般ユーザー） - COACHTECH勤怠管理</title>
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="#">
                <img src="{{ asset('img/logo.svg') }}" alt="COACHTECHロゴ画像">
            </a>
        </div>
    </header>

    <main>
        <div class="register">
            <h1 class="register__title">会員登録</h1>

            <form class="register__form" action="/attendance" method="GET">
                <div class="register__form-group">
                    <label class="register__label" for="name">名前</label>

                    <input class="register__input" type="text" id="name" name="name">
                </div>

                <div class="register__form-group">
                    <label class="register__label" for="email">メールアドレス</label>

                    <input class="register__input" type="text" id="email" name="email">
                </div>

                <div class="register__form-group">
                    <label class="register__label" for="password">パスワード</label>

                    <input class="register__input" type="password" id="password" name="password">
                </div>

                <div class="register__form-group">
                    <label class="register__label" for="password_confirmation">確認用パスワード</label>

                    <input class="register__input" type="password" id="password_confirmation" name="password_confirmation">
                </div>

                <button class="register__button" type="submit">登録する</button>
            </form>

            <div class="register__login-link">
                <a href="/">ログインはこちら</a>
            </div>
        </div>
    </main>
</body>

</html>
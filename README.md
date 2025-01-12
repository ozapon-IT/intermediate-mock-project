# COACHTECH勤怠管理アプリ
## 環境構築

### Dockerビルド

1. `git clone git@github.com:ozapon-IT/intermediate-mock-project.git`
2. `docker-compose up -d --build`

> MySQL、phpMyAdmin、MailHogは、OSによって起動しない場合があるのでそれぞれのPCに合わせて `docker-compose.yml` ファイルを編集してください。

### Laravel環境構築

1. `docker-compose exec php bash`
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed`
7. `php artisan migrate --env=testing`

> `php artisan migrate --env=testing`とした後`Would you like to create it? (yes/no) [no]`と出力されたら`yes`と入力してテスト用データベースを自動作成して下さい。

### アカウント情報

Laravel環境構築後ダミーデータのアカウントでアプリにログインできます。
- 一般ユーザー
  - メールアドレス: reina.n@coachtech.com パスワード: testreina
  - メールアドレス: taro.y@coachtech.com パスワード: testtaro
  - メールアドレス: issei.m@coachtech.com パスワード: testissei
  - メールアドレス: keikichi.y@coachtech.com パスワード: testkeikichi
  - メールアドレス: tomomi.a@coachtech.com パスワード: testtomomi
  - メールアドレス: norio.n@coachtech.com パスワード: testnorio
- 管理者ユーザー
  - メールアドレス: admin@coachtech.com パスワード: testadmin

## 使用技術

- Laravel Framework 10.48.23
- Laravel Fortify 1.24
- PHP 8.2.26 (cli)
- MySQL 9.1.0 for Linux on x86_64
- Nginx 1.27.2
- phpMyAdmin 5.2.1
- MailHog 1.14.7

---

## ER図
![COACHTECH勤怠管理アプリ:ER図](https://github.com/user-attachments/assets/12d6e4d9-a821-4b92-8027-ecaf25d038db)

---

## URL

- 開発環境 : [http://localhost](http://localhost)  
- phpMyAdmin : [http://localhost:8080](http://localhost:8080)
- MailHog : [http://localhost:8025](http://localhost:8025)

---

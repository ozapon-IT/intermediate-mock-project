<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * メールアドレスが未入力の場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_email_is_missing() : void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
            'role' => 'admin',
        ]);

        $data = [
            'email' => '',
            'password' => 'adminpassword',
        ];

        $response = $this->from(route('admin-login.show'))->post(route('admin-login.login'), $data);
        $response->assertRedirect(route('admin-login.login'));
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);

        $this->followRedirects($response)->assertSee('メールアドレスを入力してください');
    }

    /**
     * @test
     * パスワードが未入力の場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_password_is_missing() : void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
            'role' => 'admin',
        ]);

        $data = [
            'email' => 'admin@example.com',
            'password' => '',
        ];

        $response = $this->from(route('admin-login.show'))->post(route('admin-login.login'), $data);
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);

        $this->followRedirects($response)->assertSee('パスワードを入力してください');
    }

    /**
     * @test
     * 登録内容と一致しない場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_registered_information_does_not_match() : void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
            'role' => 'admin',
        ]);

        $data = [
            'email' => 'wrongadmin@example.com',
            'password' => 'adminpassword',
        ];

        $response = $this->from(route('admin-login.show'))->post(route('admin-login.login'), $data);
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);

        $this->followRedirects($response)->assertSee('ログイン情報が登録されていません');
    }
}
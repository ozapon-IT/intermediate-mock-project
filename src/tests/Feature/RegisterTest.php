<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 名前が未入力の場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_name_is_missing() : void
    {
        $data = [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    /**
     * @test
     * メールアドレスが未入力の場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_email_is_missing() : void
    {
        $data = [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /**
     * @test
     * パスワードが8文字未満の場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_password_is_too_short() : void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    /**
     * @test
     * パスワードが一致しない場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_password_confirmation_does_not_match() : void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    /**
     * @test
     * パスワードが未入力の場合、バリデーションメッセージが表示される
     */
    public function it_shows_validation_message_when_password_is_missing() : void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    /**
     * @test
     * フォームに内容が入力されていた場合、データが正常に保存される
     */
    public function it_saves_data_successfully_when_form_is_filled() : void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->post(route('register'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue(password_verify($data['password'], $user->password));
    }
}

<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    $this->user = User::factory()->create([
        'email' => 'testuser@example.com',
        'is_active' => true
    ]);
});

test('guest can view forgot password page', function () {
    $response = $this->get(route('password.request'));
    $response->assertStatus(200);
});

test('guest can request password reset link with valid email', function () {
    $response = $this->post(route('password.email'), [
        'email' => 'testuser@example.com'
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
});

test('guest cannot request password reset link with invalid email', function () {
    $response = $this->post(route('password.email'), [
        'email' => 'nonexistent@example.com'
    ]);

    $response->assertSessionHasErrors('email');
});

test('guest can view reset password page with valid token', function () {
    $token = Password::broker()->createToken($this->user);

    $response = $this->get(route('password.reset', [
        'token' => $token,
        'email' => 'testuser@example.com'
    ]));

    $response->assertStatus(200);
    $response->assertSee('testuser@example.com');
});

test('guest can reset password with valid token and password', function () {
    $token = Password::broker()->createToken($this->user);

    $response = $this->post(route('password.store'), [
        'token' => $token,
        'email' => 'testuser@example.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123'
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
});

test('guest cannot reset password with mismatched password confirmation', function () {
    $token = Password::broker()->createToken($this->user);

    $response = $this->post(route('password.store'), [
        'token' => $token,
        'email' => 'testuser@example.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'differentpassword'
    ]);

    $response->assertSessionHasErrors('password');
});

test('guest cannot reset password with invalid token', function () {
    $response = $this->post(route('password.store'), [
        'token' => 'invalid-token',
        'email' => 'testuser@example.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123'
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertFalse(Hash::check('newpassword123', $this->user->fresh()->password));
});

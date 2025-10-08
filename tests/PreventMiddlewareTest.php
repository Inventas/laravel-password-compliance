<?php

use Inventas\PasswordCompliance\Tests\Fixtures\User;

it('allows access to the change page when the user is required to change password', function () {
    // Ensure the package config is set for the test
    config()->set('password-compliance.redirect_url', '/home');

    // Define routes used in the test
    app('router')->get('/password/change', function () {
        return 'change page';
    })->name('password.change');

    app('router')->get('/change', function () {
        return 'change controller';
    })->middleware(['auth', 'password.compliance.prevent'])->name('change.route');

    // Create and authenticate a user
    $user = User::create(['email' => 'prevent-allow@example.com']);

    // require a password change (immediate)
    $user->requirePasswordChange(null, 'admin');

    $this->actingAs($user);

    // Access the route and expect successful access
    $response = $this->get('/change');

    $response->assertOk();
});

it('redirects away from the change page when the user is not required to change password', function () {
    config()->set('password-compliance.redirect_url', '/home');

    app('router')->get('/password/change', function () {
        return 'change page';
    })->name('password.change');

    app('router')->get('/change', function () {
        return 'change controller';
    })->middleware(['auth', 'password.compliance.prevent'])->name('change.route');

    // Create and authenticate a user
    $user = User::create(['email' => 'prevent-deny@example.com']);

    // Ensure not required
    $this->actingAs($user);

    $response = $this->get('/change');

    $response->assertRedirect('/home');
});

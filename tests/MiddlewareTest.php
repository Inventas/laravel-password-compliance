<?php

use Inventas\PasswordCompliance\Tests\Fixtures\User;

it('redirects to the configured password change page when requirement is active', function () {
    // Ensure the package config is set for the test
    config()->set('password-compliance.redirect_route', 'password.change');
    config()->set('password-compliance.redirect_url', '/password/change');
    config()->set('password-compliance.exempt_routes', ['password.change']);

    // Define routes used in the test
    app('router')->get('/password/change', function () {
        return 'change page';
    })->name('password.change');

    app('router')->get('/protected', function () {
        return 'ok';
    })->middleware(['auth', 'password.compliance'])->name('protected');

    // Create and authenticate a user
    $user = User::create(['email' => 'middleware@example.com']);

    // require a password change (indefinite)
    $user->requirePasswordChange(null, 'admin');

    $this->actingAs($user);

    // Access the protected route and expect a redirect to the change page
    $response = $this->get('/protected');

    $response->assertRedirect('/password/change');
});


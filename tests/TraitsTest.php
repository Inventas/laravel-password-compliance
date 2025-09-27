<?php

use Inventas\PasswordCompliance\Tests\Fixtures\User;

it('can require and clear a password change using the trait', function () {
    $user = User::create(['email' => 'test@example.com']);

    expect($user->isPasswordChangeRequired())->toBeFalse();

    // enforce in the future -> not required yet under the new semantics
    $user->requirePasswordChange(now()->addHour(), 'policy');

    $fresh = $user->fresh();

    expect($fresh->isPasswordChangeRequired())->toBeFalse();

    // enforce immediately -> required
    $user->requirePasswordChange(null, 'immediate');

    $fresh = $user->fresh();

    expect($fresh->isPasswordChangeRequired())->toBeTrue();

    // clear requirement
    $user->clearPasswordRequirement();

    expect($user->fresh()->isPasswordChangeRequired())->toBeFalse();
});

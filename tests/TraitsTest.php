<?php

use Inventas\PasswordCompliance\Tests\Fixtures\User;
use Carbon\Carbon;

it('can require and clear a password change using the trait', function () {
    $user = User::create(['email' => 'test@example.com']);

    expect($user->isPasswordChangeRequired())->toBeFalse();

    $user->requirePasswordChange(now()->addHour(), 'policy');

    $fresh = $user->fresh();

    expect($fresh->isPasswordChangeRequired())->toBeTrue();

    // clear requirement
    $user->clearPasswordRequirement();

    expect($user->fresh()->isPasswordChangeRequired())->toBeFalse();
});


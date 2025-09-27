<?php

use Inventas\PasswordCompliance\Mail\InviteUser;

it('builds invite email with provided data', function () {
    $email = 'newuser@example.com';
    $name = 'New User';
    $initialPassword = 'initialSecret123!';

    $mailable = new InviteUser($email, $name, $initialPassword, "https://example.org/");

    // Render the mailable to HTML and assert the important pieces are present
    $rendered = $mailable->render();

    expect($rendered)->toContain($email);
    expect($rendered)->toContain($name);
    expect($rendered)->toContain($initialPassword);

    // Subject should be set to our invite subject
    expect($mailable->subject)->toContain('invited');
});

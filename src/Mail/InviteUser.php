<?php

namespace Inventas\PasswordCompliance\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteUser extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $emailAddress,
        public ?string $name,
        public string $initialPassword,
        public ?string $customSubject = null,
    ) {}
    public function build()
    {
        $subject = $customSubject ?? __('You have been invited — please sign in');

        return $this->subject($subject)
            ->markdown('password-compliance::emails.invite')
            ->with([
                'email' => $this->emailAddress,
                'name' => $this->name,
                'initialPassword' => $this->initialPassword,
            ]);
    }
}

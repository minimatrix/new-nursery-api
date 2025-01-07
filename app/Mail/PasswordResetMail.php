<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetToken;

    public function __construct($resetToken)
    {
        $this->resetToken = $resetToken;
    }

    public function build()
    {
        return $this->markdown('emails.password-reset')
            ->subject('Reset Your Password');
    }
}

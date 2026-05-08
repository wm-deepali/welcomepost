<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;

    public function __construct($otp, $userName)
    {
        $this->otp = $otp;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Your OTP Code for Email Change')
                    ->view('email.email-otp')
                    ->with([
                        'otp' => $this->otp,
                        'userName' => $this->userName,
                    ]);
    }
}

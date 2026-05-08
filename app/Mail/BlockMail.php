<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BlockMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $blockReason;

    public function __construct($subject, $blockReason)
    {
        $this->subject = $subject;
        $this->blockReason = $blockReason;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('email.block-notification');
    }
}

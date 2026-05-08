<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($customerName, $ticketSubject, $messageContent, $ticketImage)
    {
        $this->customerName = $customerName;
        $this->ticketSubject = $ticketSubject;
        $this->messageContent = $messageContent;
        $this->ticketImage = $ticketImage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
    {
        $email = $this->view('email.ticket')
                      ->subject($this->ticketSubject)
                      ->with([
                          'customerName' => $this->customerName,
                          'ticketSubject' => $this->ticketSubject,
                          'messageContent' => $this->messageContent,
                      ]);

        if ($this->ticketImage) {
            $email->attach($this->ticketImage);
        }

        return $email;
    }
}

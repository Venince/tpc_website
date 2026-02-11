<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $msg) {}

    public function build()
    {
        return $this
            ->subject('[TPC Website] ' . $this->msg->subject)
            // Reply button will reply to the sender:
            ->replyTo($this->msg->email, $this->msg->name)
            ->view('emails.contact.received');
    }
}

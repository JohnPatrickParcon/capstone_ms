<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCredentials extends Mailable
{
    use Queueable, SerializesModels;
    public $user_data;

    public function __construct($data)
    {
        $this->user_data = $data;
    }

    public function build()
    {
        return $this->markdown('mails.adviserCredentials')->subject("CLSU CMS Adviser Credentials");
    }
}

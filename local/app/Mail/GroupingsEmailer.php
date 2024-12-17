<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupingsEmailer extends Mailable
{
    use Queueable, SerializesModels;
    public $user_data;

    public function __construct($data)
    {
        $this->user_data = $data;
    }

    public function build()
    {
        return $this->markdown('mails.groupingsMailer')->subject("CLSU CMS Groupings Notification");
    }
}

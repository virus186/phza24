<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    
    use Queueable,SerializesModels;

    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // public function __construct($token)
    // {
    //     $this->token = $token;
    // }



    public $array;
    public function __construct($array)
    {
        $this->array = $array;
    }


    public function build()
    {
        return $this->view('backEnd.template')
                    ->from($this->array['from'], env('MAIL_FROM_NAME'))
                    ->subject($this->array['subject']);
    }
}

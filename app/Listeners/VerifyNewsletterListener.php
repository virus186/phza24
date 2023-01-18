<?php

namespace App\Listeners;

use App\Events\VerifyNewsletter;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerifyNewsletterListener
{
    use SendMail;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(VerifyNewsletter $event)
    {
        return $this->sendNewsletterVerifyMail($event->data);
    }
}

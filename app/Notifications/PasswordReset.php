<?php

namespace App\Notifications;

use App\Traits\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Modules\GeneralSetting\Entities\EmailTemplate;

class PasswordReset extends Notification
{
    use Queueable, SendMail;

    public $token;
    public static $createUrlCallback;
    public static $toMailCallback;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $tamplate = EmailTemplate::where('type_id', 41)->where('is_active', 1)->first();
        $subject= $tamplate->subject;
        $body = $tamplate->value;


        $key = ['http://{RESET_LINK}','{RESET_LINK}','{WEBSITE_NAME}','{EMAIL_SIGNATURE}'];
        $value = [$this->resetUrl($notifiable),$this->resetUrl($notifiable),app('general_setting')->site_title,app('general_setting')->mail_signature];
        $body = str_replace($key, $value, $body);

        return (new MailMessage)
            ->view('emails.mail',["body" => $body])->subject($subject);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use PhoneAuth\Support\Helpers\Channels\TextMessageChannel;

class VerifyPhoneNumber extends Notification
{
    use Queueable;

    /**
     * The verification token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
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
        return [TextMessageChannel::class];
    }

    /**
     * Get the text message representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toTextMessage($notifiable)
    {
        return [
            'message'   => [
                'pattern_code'  => config('phoneauth.channels.sms.pattern'),
                'originator'    => config('phoneauth.channels.sms.origin'),
                'recipient'     => "$notifiable->number" ,
                'values'        =>[
                    'order_id'  => "$this->token"
                ]
            ],
            'url'       => 'messages/patterns/send'
        ];
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
}

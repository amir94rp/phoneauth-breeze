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
                'TemplateId'    => config('phoneauth.channels.sms.verifyPattern'),
                'UserApiKey'    => config('phoneauth.channels.sms.apikey'),
                'SecretKey'     => config('phoneauth.channels.sms.secret'),
                'Mobile'        => "$notifiable->number" ,
                'ParameterArray'    => [
                    [
                        'Parameter'         => 'verificationCode',
                        'ParameterValue'    => $this->token
                    ]
                ]
            ],
            'url'       => 'UltraFastSend/UserApiKey'
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

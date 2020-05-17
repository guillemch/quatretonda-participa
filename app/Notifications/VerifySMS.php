<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Messagebird\MessagebirdChannel;
use NotificationChannels\Messagebird\MessagebirdMessage;

class VerifySMS extends Notification
{

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MessagebirdChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMessageBird($notifiable)
    {
        $phone = str_replace(".","",$notifiable->SMS_phone);
        return (new MessagebirdMessage)
                    ->setRecipients($phone)
                    ->setBody(__('participa.SMS_notification', ['code' => $notifiable->SMS_token]));
    }

}

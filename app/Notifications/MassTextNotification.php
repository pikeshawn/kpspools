<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
// use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class MassTextNotification extends Notification
{
    use Queueable;

    protected $textMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($textMessage)
    {
        //        dd('text message constructor');

        $this->textMessage = $textMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    public function via($notifiable): array
    {
        return ['vonage'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toVonage($notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->clientReference((string) $notifiable->routes['vonage'])
            ->content($this->textMessage);
    }
}

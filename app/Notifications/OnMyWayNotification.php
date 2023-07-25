<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\ServiceStop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
//use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class OnMyWayNotification extends Notification
{
    use Queueable;

    protected $textMessage;
    protected $customerName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($textMessage, $customerName = null)
    {
        $this->textMessage = $textMessage;
        $this->customerName = $customerName;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toVonage($notifiable): VonageMessage
    {
        if ($this->customerName) {
            return (new VonageMessage)
                ->clientReference((string) $notifiable->routes['vonage'])
                ->content($this->textMessage . " sent to:: " . $this->customerName);
        } else {
            return (new VonageMessage)
                ->clientReference((string) $notifiable->routes['vonage'])
                ->content($this->textMessage);
        }


    }

    public function correctValue($value)
    {
        if ($value == 1) {
            return 'Yes';
        } elseif ($value == 0 || $value == null) {
            return 'No';
        }
    }
}

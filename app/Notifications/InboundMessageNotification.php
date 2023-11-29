<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;

class InboundMessageNotification extends Notification
{
    use Queueable;

    protected $customerName, $textBody, $customerNumber;

    /**
     * Create a new notification instance.
     */
    public function __construct($name, $message, $number)
    {
        //
        $this->customerName = $name;
        $this->textBody = $message;
        $this->customerNumber = $number;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['vonage'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toVonage(object $notifiable): VonageMessage
    {

        $message = "$this->customerName has sent you a message::\n\n$this->textBody\n\n from this number $this->customerNumber";

        return (new VonageMessage)
            ->clientReference((string) $notifiable->routes['vonage'])
            ->content($message);
    }
}

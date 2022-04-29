<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\ServiceStop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class ServiceStopCompleted extends Notification
{
    use Queueable;
    protected $service_stop, $customer, $address, $isAdmin;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ServiceStop $service_stop, Customer $customer, $address, $isAdmin = false)
    {
        $this->service_stop = $service_stop;
        $this->customer = $customer;
        $this->address = $address;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['nexmo'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
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
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {

        $vacuum = $this->correctValue($this->service_stop->vacuum);
        $brush = $this->correctValue($this->service_stop->brush);
        $empty_baskets = $this->correctValue($this->service_stop->empty_baskets);
        $backwash = $this->correctValue($this->service_stop->backwash);

        $text = $this->customer->first_name . " " .
            $this->customer->last_name . " your pool has been completed by "
            . $this->customer->assigned_serviceman . "\n" .
            "address:    " . $this->address[0]->address_line_1 . "\n" .
            "time in:     " . $this->service_stop->time_in . "\n" .
            "time out:   " . $this->service_stop->time_out . "\n" .
            "pH:                " . $this->service_stop->ph_level . "\n" .
            "chlorine:        " . $this->service_stop->chlorine_level . "\n" .
            "tabs:              " . $this->service_stop->tabs_whole_mine . "\n" .
            "liquid chlorine:   " . $this->service_stop->liquid_chlorine . "\n" .
            "acid:              " . $this->service_stop->liquid_acid . "\n" .
            "vacuum:         " . $vacuum . "\n" .
            "brush:            " . $brush . "\n" .
            "emptied baskets:   " . $empty_baskets . "\n" .
            "backwash:      " . $backwash;

        if ($this->isAdmin) {
            $text = $text . "\nnotes: " . $this->service_stop->notes . "\n";
        }


        return (new NexmoMessage)
            ->content($text);
    }

    public function correctValue($value)
    {
        if ($value == 1) {
            return 'Yes';
        } else if ($value == 0 || $value == null) {
            return 'No';
        }
    }
}

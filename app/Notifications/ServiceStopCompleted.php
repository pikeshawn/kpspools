<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\ServiceStop;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ServiceStopCompleted extends Notification
{
    use Queueable;

    protected $service_stop;

    protected $customer;

    protected $address;

    protected $isAdmin;

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

        $vacuum = $this->correctValue($this->service_stop->vacuum);
        $brush = $this->correctValue($this->service_stop->brush);
        $empty_baskets = $this->correctValue($this->service_stop->empty_baskets);
        $backwash = $this->correctValue($this->service_stop->backwash);

        $chlorineLevel = $this->service_stop->chlorine_level;
        $chlorineLevel > 5 ? $chlorineLevel = 5.0 : $chlorineLevel = $this->service_stop->chlorine_level;

        if ($this->isAdmin) {
            $text = $this->customer->first_name.' '.$this->customer->last_name
                ."\nhttps://kpspools.com/customers/show/".$this->address->id
                ."\nhttps://kpspools.com/service_stops/".$this->address->id
                ."\nwas completed by ".Auth::user()->name;
        } else {
            $text = "Jemmson:\n".$this->customer->first_name.' '.
                $this->customer->last_name.' your pool has been completed by '
                .Auth::user()->name." from KPS Pools\n".
                'address:    '.$this->address->address_line_1."\n".
                'time in:     '.$this->service_stop->time_in."\n".
                'time out:   '.$this->service_stop->time_out."\n".
                'pH:                '.$this->service_stop->ph_level."\n".
                'chlorine:        '.$chlorineLevel."\n".
                'tabs:              '.$this->service_stop->tabs_whole_mine."\n".
                'liquid chlorine:   '.$this->service_stop->liquid_chlorine." gallon(s)\n".
                'acid:              '.$this->service_stop->liquid_acid." gallon(s)\n".
                'vacuum:         '.$vacuum."\n".
                'brush:            '.$brush."\n".
                'emptied baskets:   '.$empty_baskets."\n".
                'backwash:      '.$backwash;

            if ($this->service_stop->salt_level !== 'not checked') {
                $text = $text."\nsalt level: ".$this->service_stop->salt_level."\n";
            }

            if (! is_null($this->service_stop->phosphateLevel)) {
                $text = $text."\nPhosphate Level:    ".$this->service_stop->phosphateLevel." ppb\n";
            }

            if ($this->isAdmin) {
                $text = $text."\nnotes: ".$this->service_stop->notes."\n";
            }
        }

        return (new VonageMessage)
            ->clientReference((string) $notifiable->routes['vonage'])
            ->content($text);
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

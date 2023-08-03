<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MassTextNotification;

class NotificationsTest extends TestCase
{
    public function test_I_can_send_a_notifications(): void
    {
//        need an array of phone numbers
        $phone_numbers = [
//            "14807034902",
            '16503913212',
            '13136082806',
            '15204311877',
            '15053013162',
            '14806657845'
        ];

        $message = "DO NOT REPLY::
        Please disregard the prior message. It was sent in error. The below message is the correct one:
        ============================

        Here at KPS Pools we have gone through some rapid expansion and are making adjustments to the route. Your pool will be serviced on Wednesdays and your serviceman will be Jeremiah next week and then Phillip after that. If you have any questions, then please reach out to Shawn at 480-703-4902. Thank you";

//        $message = "DO NOT REPLY::
//        Here at KPS Pools we need to make some adjustments to the route due to our expansion. Your pool will now be serviced by Shawn.
//        Please reach out to Shawn if you have any questions. 480-703-4902";

//        $message = "DO NOT REPLY::
//        Here at KPS Pools we need to make some adjustments to the route due to our expansion. Your pool will now be serviced on Tuesdays and Zach will continue to be your service technician.
//        Please reach out to Shawn if you have any questions. 480-703-4902";

//        $message = "DO NOT REPLY::
//        Welcome to KPS Pools. Your pool will be serviced on Tuesdays and your service technician will be Jeremiah.
//        Please reach out to Shawn if you have any questions. 480-703-4902";

//        need to cycle through the numbers
//        need to call the notifications and send a specific message

        foreach ($phone_numbers as $num) {
            Notification::route('vonage', $num)
                ->notify(new MassTextNotification($message));
        }
    }
}

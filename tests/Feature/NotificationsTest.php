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
//        $phone_numbers = [
//            '16025712575',
//            '14807218881'
//        ];
//
//        $newDay = 'Monday';
//        $newServiceman = 'Shawn';
//
//        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//        self::send($phone_numbers, $text);
//
//        $phone_numbers = [
//            '16023178020'
//        ];
//
//        $newDay = 'Thursday';
//        $newServiceman = 'Jeremiah';
//
//        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//        self::send($phone_numbers, $text);
//
//        $phone_numbers = [
//            '16026190260',
//            '16026848562',
//            '14805185755',
//            '14803638650',
//            '14803239753',
//            '14806227728',
//            '13232190361',
//            '16023303918',
//            '16022910108',
//        ];
//
//        $newDay = 'Tuesday';
//        $newServiceman = 'Jeremiah';
//
//        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//        self::send($phone_numbers, $text);
//
//
//
//        $phone_numbers = [
//            '16027814453'
//        ];
//
//        $newDay = 'Tuesday';
//        $newServiceman = 'Reid';
//
//        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//        self::send($phone_numbers, $text);
//
//
//        $phone_numbers = [
//            '13525984401',
//            '14808151946',
//            '14803295034',
//        ];
//
//        $newDay = 'Tuesday';
//        $newServiceman = 'Zach';
//
//        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//        self::send($phone_numbers, $text);
//
//        $phone_numbers = [
//            '14808265558',
//        ];
//
//        $newDay = 'Wednesday';
//        $newServiceman = 'Phillip';
//
//        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//        self::send($phone_numbers, $text);
//
//
//        $phone_numbers = [
//            '14808288842',
//            '14806229250',
//        ];
//
//        $newDay = 'Wednesday';
//        $newServiceman = 'Shawn';
//
//        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//        self::send($phone_numbers, $text);


//        $phone_numbers = [
//            '17606966398',
//            '14807108935',
//            '17076885285',
//            '19253393726',
//        ];
//
//        $newDay = 'Monday';
//        $currentServiceman = 'Shawn';
//        $text = self::differentDaySameServiceman($newDay, $currentServiceman);
//        self::send($phone_numbers, $text);
//
//        $phone_numbers = [
//            '14802136170',
//            '14803640757',
//            '16027407511',
//        ];
//
//        $newDay = 'Thursday';
//        $currentServiceman = 'Shawn';
//        $text = self::differentDaySameServiceman($newDay, $currentServiceman);
//        self::send($phone_numbers, $text);
//
//        $phone_numbers = [
//            '13856263351',
//            '16029311133',
//            '17205056477',
//        ];
//
//        $newDay = 'Wednesday';
//        $currentServiceman = 'Shawn';
//        $text = self::differentDaySameServiceman($newDay, $currentServiceman);
//        self::send($phone_numbers, $text);
//
//
//        $phone_numbers = [
//            '14802397745',
//            '14802023866',
//        ];
//
//        $newDay = 'Wednesday';
//        $currentServiceman = 'Zach';
//        $text = self::differentDaySameServiceman($newDay, $currentServiceman);
//        self::send($phone_numbers, $text);


//        $phone_numbers = [
//            '14807034902'
//        ];

        $phone_numbers = [
            '14802392801',
            '12062351787',
            '13019052465',
        ];

        $currentDay = 'Thursday';
        $newSrviceman = 'Jeremiah';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);


        $phone_numbers = [
            '14807487760',
            '14806286607',
            '15202372437',
            '14806286607',
        ];

        $currentDay = 'Thursday';
        $newSrviceman = 'Shawn';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);

        $phone_numbers = [
            '16023124131',
            '16023326876',
            '14807590483',
            '14802169397',
            '16027702667',
            '19083776011',
            '14802504998',
        ];

        $currentDay = 'Thursday';
        $newSrviceman = 'Zach';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);


        $phone_numbers = [
            '13202823677',
            '14805676186',
            '15202396063',
            '16025410303',
            '14802213315',
            '16158610479',
            '14803189416',
            '14804178647',
        ];

        $currentDay = 'Tuesday';
        $newSrviceman = 'Jeremiah';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);



        $phone_numbers = [
            '14808618840',
            '14802503797',
            '16029205582',
            '19102796068',
            '18052081347',
            '12145496564',
        ];

        $currentDay = 'Tuesday';
        $newSrviceman = 'Reid';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);


        $phone_numbers = [
            '14803351963',
            '14805703770',
            '14804140108',
            '14806940020',
            '14803295073',
            '16023097094',
            '14809078827',
            '16029094785',
            '14805708338',
            '18083494052',
            '14807038320',
        ];

        $currentDay = 'Tuesday';
        $newSrviceman = 'Shawn';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);



        $phone_numbers = [
            '14802159123',
            '16238260681',
            '14807200743',
            '14808180166',
            '14804343425',
            '14805803711',
            '13036693723',
        ];

        $currentDay = 'Tuesday';
        $newSrviceman = 'Zach';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);


        $phone_numbers = [
            '13136082806',
            '14806657845',
            '16503913212',
            '15053013162',
            '15204311877',
        ];

        $currentDay = 'Wednesday';
        $newSrviceman = 'Phillip';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);

        $phone_numbers = [
            '16026142226',
            '14158064083',
        ];

        $currentDay = 'Wednesday';
        $newSrviceman = 'Reid';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);

        $phone_numbers = [
            '14802333873',
        ];

        $currentDay = 'Wednesday';
        $newSrviceman = 'Zach';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);

        $phone_numbers = [
            '16025706794'
        ];

        $currentDay = 'Tuesday';
        $newSrviceman = 'Jeremiah';
        $text = self::sameDayDifferentServiceman($currentDay, $newSrviceman);
        self::send($phone_numbers, $text);
//
//
//        $message = "DO NOT REPLY::
//        Please disregard the prior message. It was sent in error. The below message is the correct one:
//        ============================
//
//        Here at KPS Pools we have gone through some rapid expansion and are making adjustments to the route. Your pool will be serviced on Wednesdays and your serviceman will be Jeremiah next week and then Phillip after that. If you have any questions, then please reach out to Shawn at 480-703-4902. Thank you";

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
    }

    private function dayAndServiceManChangedMessage($newDay, $newServiceman)
    {
        return "We appreciate your patience as we continue to grow. We have to change the day your pool is serviced and the service technician who will be servicing your pool. Your new day will be " . $newDay . " and your service technician will be " . $newServiceman . ". Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
    }

    private function differentDaySameServiceman($newDay, $currentServiceman)
    {
        return "We appreciate your patience as we continue to grow. We have to change the day your pool is serviced but your service technician will remain the same. Your new day will be " . $newDay . ". Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
    }

    private function sameDayDifferentServiceman($currentDay, $newSrviceman)
    {
        return "We appreciate your patience as we continue to grow. We have to change the service technician who will be servicing your pool. Your service technician will be " . $newSrviceman . ". Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
    }

    private function send($phone_numbers, $message)
    {
        foreach ($phone_numbers as $num) {
            Notification::route('vonage', $num)
                ->notify(new MassTextNotification($message));
        }
    }
}

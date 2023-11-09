<?php

namespace Tests\Feature\Notifications;

use App\Notifications\MassTextNotification;
use http\Message;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VacationBillNotificationTest extends TestCase
{

//  vendor/bin/phpunit /Users/shawnpike/Documents/code/business/kpspools/tests/Feature/Notifications/VacationBillNotificationTest.php


//    public function test_sending_vacation_notifications()
//    {
//        $fp = @fopen("/Users/shawnpike/Documents/code/business/kpspools/tests/Feature/Notifications/vacationEntries.csv", "r");
//
//        $begin = "Holiday vacation time is upon us! We'll be on vacation from November 13th to November 24th, and from December 19th to January 1st, resuming service on January 2nd. Our billing is based on a 4-week month and there are four months in the year when we serviced your pool for 5 weeks. We make up for those extra unbilled weeks during the holiday period, when billing will continue as normal. \n";
//
//        $threeWeeks = "We serviced your pool for 3 extra weeks, so you will be billed the full amount for November and for 3 weeks in December.\n";
//        $twoWeeks = "We serviced your pool for 2 extra weeks, so you will be billed for 3 weeks in November and for 3 weeks in December.\n";
//        $oneWeek = "We serviced your pool for 1 extra week, so you will be billed for 3 weeks in November and for 2 weeks in December.\n";
//        $noWeeks = "Since we did not service your pool for any extra weeks you will be billed for 2 weeks in November and for 2 weeks in December.\n";
//
//        $end = "If this schedule causes any problems for you or your pool, please let us know, and we'll be glad to make any necessary adjustments. You can reach Shawn at 480-703-4902 or 480-622-6441.";
//
//
//        if ($fp) {
//            while (($buffer = fgets($fp, 4096)) !== false) {
////                echo $buffer;
//                $line = explode(',', $buffer);
//                $first_name = $line[0];
//                $phone = $line[1];
//                $last_name = $line[2];
//                $numberOfWeeks = $line[3];
//                $type = $line[5];
//                $nov_plan_price = $line[6];
//                $dec_plan_price = $line[7];
//
//                $message = "DO NOT REPLY\n============\nHello $first_name,\n";
//
//                if ($type == 'full') {
//                    $message = $message . $begin . $end;
//                } else if ($numberOfWeeks == 3) {
//                    $message = $message . $begin . $threeWeeks . $end;
//                } else if ($numberOfWeeks == 2) {
//                    $message = $message . $begin . $twoWeeks . $end;
//                } else if ($numberOfWeeks == 1) {
//                    $message = $message . $begin . $oneWeek . $end;
//                } else if ($numberOfWeeks == 0) {
//                    $message = $message . $begin . $noWeeks . $end;
//                }
//
//                echo $message;
//                $phone_numbers = [
//                    $phone
//                ];
//                self::send($phone_numbers, $message);
////                dd($message);
//            }
//            if (!feof($fp)) {
//                echo "Error: unexpected fgets() fail\n";
//            }
//            fclose($fp);
//        }
//    }

    public function test_send_vacation_bill_update()
    {
        $phone_numbers = [
            '14807034902',
            '12059145284',
            '12062351787',
            '12063515869',
            '12066609995',
            '12142360616',
            '12152722968',
            '12402372956',
            '13019052465',
            '13036693723',
            '13136082806',
            '14158064083',
            '14802136170',
            '14802159123',
            '14802211277',
            '14802277622',
            '14802418361',
            '14802426621',
            '14802503797',
            '14802621782',
            '14802767814',
            '14802966330',
            '14803095048',
            '14803261828',
            '14803261916',
            '14803309120',
            '14803351963',
            '14803402765',
            '14803435876',
            '14803560115',
            '14803640757',
            '14804448921',
            '14804604888',
            '14805676186',
            '14805703770',
            '14805708338',
            '14805803711',
            '14806657845',
            '14807030971',
            '14807038320',
            '14807104112',
            '14807179638',
            '14807204070',
            '14807487760',
            '14807590483',
            '14807883299',
            '14808039112',
            '14808265558',
            '14808288842',
            '14808616169',
            '14808618840',
            '14808886460',
            '14809078827',
            '15038169150',
            '15053013162',
            '15103355788',
            '15202716952',
            '15204311877',
            '15708156083',
            '16023124131',
            '16023178020',
            '16023326876',
            '16023359897',
            '16023889819',
            '16024301105',
            '16025459053',
            '16026008899',
            '16026841135',
            '16026951331',
            '16027029668',
            '16027407511',
            '16027630536',
            '16027702667',
            '16027844214',
            '16029086165',
            '16029205582',
            '16029206994',
            '16029995361',
            '16238260681',
            '16306702991',
            '16503913212',
            '17607748119',
            '17703242326',
            '18083494052',
            '18476020795',
            '18609861739',
            '19083776011',
            '12152626553',
            '14802333873',
            '14806229250',
            '14806369437',
            '14806503218',
            '14806599685',
            '14807213022',
            '14808619114',
            '15053212807',
            '15417602965',
            '15417602965',
            '15417602965',
            '16025704363',
            '16027931877',
            '17023067056',
            '19083918862',
            '19102796068'
        ];
        $message = "DO NOT REPLY\n============\n";
        $meat = 'Just a quick update on this months bill. Since, you are billed at the end of the month, the prorated bill for November will be on December 1st and the prorated bill for December will be on January 1st. ';
        $end = " If you have any questions the you can reach Shawn at 480-703-4902 or 480-622-6441.";

        $message = $message . $meat . $end;

        self::send($phone_numbers, $message);

    }

    private function send($phone_numbers, $message)
    {
//        dd($phone_numbers[0]);

        foreach ($phone_numbers as $num) {
            Notification::route('vonage', $num)
                ->notify(new MassTextNotification($message));
        }
    }
}

<?php

namespace Tests\Feature\Notifications;

use App\Notifications\MassTextNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{

//  vendor/bin/phpunit /Users/shawnpike/Documents/code/business/kpspools/tests/Feature/NotificationsTest.php


    public function test_sending_reminder_for_bill_payment()
    {
        $fp = @fopen("/Users/shawnpike/Documents/code/business/kpspools/scratch/Billing/2023/10_October/unpaid_test.csv", "r");
        if ($fp) {
            while (($buffer = fgets($fp, 4096)) !== false) {
//                echo $buffer;
                $line = explode(',', $buffer);
                $first_name = $line[1];
                $last_name = $line[2];
                $phone = $line[3];
                $invoice_name = $line[4];
                $bid_price = $line[5];
                $invoice_date = $line[7];
                $signedUpStatus = $line[8];
                $link1 = $line[9];
                $link2 = $line[10];
                $link3 = $line[11];

                $bid_price = $bid_price / 100;

                if ($signedUpStatus == 'signed up') {
                    $message = "DO NOT REPLY\n============\nHello $first_name, You have an outstanding bill:\nInvoice Name::\n$invoice_name\n$$bid_price that was billed on $invoice_date. You can use one of the following links to access it:\n\n$link1\n\n$link2\n\n$link3\nPlease reach out to Shawn if you think that this is an error or you have any questions at 480-703-4902 or 480-622-6441.";
                } else {
                    $message = "DO NOT REPLY\n============\nHello $first_name, You have an outstanding bill:\nInvoice Name::\n$invoice_name\n$$bid_price that was billed on $invoice_date. You can use one of the following links to access it:\n\n$link1\n\n$link2\n\n$link3\nYou will be prompted to sign up for the application. Once signed up, you will be taken to the invoice where you can scroll down and pay.\n\nIf you are not taken to the invoice:\n1. Select Jobs at the bottom of the application.\n2. Select approved at the top of the page.\n3. Select the blue box to view the invoice.\n4. Select 'Pay with Credit Card' at the bottom of the invoice.\n5. Enter your credit card information to make payment.\n\nPlease reach out to Shawn if you think that this is an error or you have any questions: 480-703-4902 or 480-622-6441.";
                }

                $message = "";

                echo $message;
                $phone_numbers = [
                    $phone
                ];
                self::send($phone_numbers, $message);
            }
            if (!feof($fp)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($fp);
        }
    }


//    public function test_I_can_send_a_notifications(): void
//    {
////////        need an array of phone numbers
//        $elias = [
//            '14807034902'
////            '16023359897',
////            '14805708338',
////            '14807038320',
////            '14808886460',
////            '14803351963',
////            '18083494052',
////            '14802277622',
////            '14807590483',
////            '14807030971',
////            '14802169397',
////            '19176851795',
////            '14802061258'
//        ];
//////
////////
//        $text = self::delayedDayMessage();
//        self::send($elias, $text);
////
////
////        $jeremiah = [
//////            '14807034902',
////            '14807030971'
////        ];
////
//////
////        $text = self::welcomeToKPS('Friday', 'Jeremiah');
////        self::send($jeremiah, $text);
////
////        $jeremiah = [
//////            '14807034902',
////            '15072724813',
////            '16092732934'
////        ];
////
//////
////        $text = self::welcomeToKPS('Tuesday', 'Jeremiah');
////        self::send($jeremiah, $text);
////
////        $jeremiah = [
//////            '14807034902',
////            '14805676186',
////            '14805185755'
////        ];
////
//////
////        $text = self::differentDaySameServiceman('Wednesday', 'Jeremiah');
////        self::send($jeremiah, $text);
////
////
////        $jeremiah = [
//////            '14807034902',
////            '14805866570'
////        ];
////
//////
////        $text = self::welcomeToKPS('Thursday', 'Reid');
////        self::send($jeremiah, $text);
////
////        $jeremiah = [
//////            '14807034902',
////            '19176851795'
////        ];
////
//////
////        $text = self::welcomeToKPS('Friday', 'Shawn');
////        self::send($jeremiah, $text);
////
////        $jeremiah = [
//////            '14807034902',
////            '14806125633'
////        ];
////
//////
////        $text = self::welcomeToKPS('Monday', 'Shawn');
////        self::send($jeremiah, $text);
////
////        $jeremiah = [
//////            '14807034902',
////            '16023990191'
////        ];
////
//////
////        $text = self::welcomeToKPS('Tuesday', 'Shawn');
////        self::send($jeremiah, $text);
////
////        $jeremiah = [
//////            '14807034902',
////            '16236926844'
////        ];
////
//////
////        $text = self::welcomeToKPS('Tuesday', 'Zach');
////        self::send($jeremiah, $text);
////
//////
////////
//////////        $phone_numbers = [
//////////            '16023199626'
//////////        ];
////////////
//////////        $newDay = 'Tuesday';
//////////        $newServiceman = 'Jeremiah';
//////////
//////////        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//////////        self::send($phone_numbers, $text);
//////////
//////////        $phone_numbers = [
//////////            '14802961150'
//////////        ];
////////////
//////////        $newDay = 'Tuesday';
//////////        $newServiceman = 'Zach';
//////////
//////////        $text = self::dayAndServiceManChangedMessage($newDay, $newServiceman);
//////////        self::send($phone_numbers, $text);
//////////
//////////        $phone_numbers = [
//////////            '17208109650'
//////////        ];
//////////
//////////        $newDay = 'Monday';
//////////        $newServiceman = 'Jeremiah';
//////////
//////////        $text = self::welcomeToKPS($newDay, $newServiceman);
//////////        self::send($phone_numbers, $text);
//////////
//////////        $phone_numbers = [
//////////            '16023808900',
//////////            '14802256369'
//////////        ];
//////////
//////////        $newDay = 'Monday';
//////////        $newServiceman = 'Shawn';
//////////
//////////        $text = self::welcomeToKPS($newDay, $newServiceman);
//////////        self::send($phone_numbers, $text);
//////////
//////////        $phone_numbers = [
//////////            '14086238007'
//////////        ];
//////////
//////////        $newDay = 'Tuesday';
//////////        $newServiceman = 'Jeremiah';
//////////
//////////        $text = self::welcomeToKPS($newDay, $newServiceman);
//////////        self::send($phone_numbers, $text);
//////////
//////////        $phone_numbers = [
//////////            '15419104514'
//////////        ];
//////////
//////////        $newDay = 'Tuesday';
//////////        $newServiceman = 'Shawn';
//////////
//////////        $text = self::welcomeToKPS($newDay, $newServiceman);
//////////        self::send($phone_numbers, $text);
//////////
//////////        $phone_numbers = [
//////////            '14805703663'
//////////        ];
//////////
//////////        $newDay = 'Tuesday';
//////////        $newServiceman = 'Jeremiah';
//////////
//////////        $text = self::differentDaySameServiceman($newDay, $newServiceman);
//////////        self::send($phone_numbers, $text);
//    }


    private function delayedDayMessage()
    {
        return "DO NOT REPLY::\nDue to further vehicle issues. Elias was not able to get to all the pools today. Really sorry for the delay. Your pool will definitely be cleaned tomorrow. Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
    }

    private function temporarySwitchDayMessage()
    {
//        return "Sorry for the late notice but Jeremiah has been working through some vehicle issues, so he will be servicing your pool tomorrow. Please reach out to Shawn if you have any questions at 480-703-4902 or 480-622-6441. Really apologize for the delay in cleaning your pool and we really appreciate your patience.";
        return "DO NOT REPLY::\nSorry for the late notice but Zach was unable to get to your pool today, so Shawn will be servicing your pool tomorrow. Please reach out to Shawn if you have any questions at 480-703-4902 or 480-622-6441. Really apologize for the delay in cleaning your pool and we really appreciate your patience.";
    }

    private function welcomeToKPS($newDay, $newServiceman)
    {
        return "DO NOT REPLY::\nWelcome to KPS Pools. We appreciate you joining our family and giving us the opportunity to service your pool. $newServiceman will be servicing your pool on $newDay. Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
    }

    private function dayAndServiceManChangedMessage($newDay, $newServiceman)
    {
        return "DO NOT REPLY::\nWe appreciate your patience as we continue to grow. We have to change the day your pool is serviced and the service technician who will be servicing your pool. Your new day will be " . $newDay . " and your service technician will be " . $newServiceman . ". Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
    }

    private function differentDaySameServiceman($newDay, $currentServiceman)
    {
        return "DO NOT REPLY::\nWe appreciate your patience as we continue to grow. We have to change the day your pool is serviced but your service technician will remain the same. Your new day will be " . $newDay . ". Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
    }

    private function sameDayDifferentServiceman($newServiceman)
    {
        return "DO NOT REPLY::\nWe appreciate your patience as we continue to grow. We have to change the service technician who will be servicing your pool. Your new service technician will be " . $newServiceman . ". Please reach out to Shawn if you have any questions 480-703-4902 or 480-622-6441.";
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

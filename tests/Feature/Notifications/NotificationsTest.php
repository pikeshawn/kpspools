<?php

namespace Tests\Feature\Notifications;

use App\Notifications\MassTextNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{

//  vendor/bin/phpunit /Users/shawnpike/Documents/code/business/kpspools/tests/Feature/Notifications/NotificationsTest.php


    public function test_sending_reminder_for_bill_payment()
    {
        $fp = @fopen("/Users/shawnpike/Documents/code/business/kpspools/scratch/Billing/2023/12_December/unpaid_test.csv", "r");
        if ($fp) {
            while (($buffer = fgets($fp, 4096)) !== false) {
//                echo $buffer;
                $line = explode(',', $buffer);
                $first_name = $line[1];
                $last_name = $line[2];
                $phone = $line[4];
                $invoice_name = $line[5];
                $bid_price = $line[6];
                $invoice_date = $line[8];
                $signedUpStatus = $line[9];
                $link1 = $line[10];
                $link2 = $line[11];
                $link3 = $line[12];

//                dd("
//                    $first_name\n
//                    $phone\n
//                    $invoice_name\n
//                    $bid_price\n
//                    $invoice_date\n
//                    $link1\n
//                    $link2\n
//                    $link3\n
//                ");

                $bid_price = $bid_price / 100;

//                if ($signedUpStatus == 'signed up') {
//                    $message = "DO NOT REPLY\n============\nHello $first_name, You have an outstanding bill:\nInvoice Name::\n$invoice_name\n$$bid_price that was billed on $invoice_date. You can use one of the following links to access it:\n\n$link1\n\n$link2\n\n$link3\nPlease reach out to Shawn if you think that this is an error or you have any questions at 480-703-4902 or 480-622-6441.";
//                } else {
//                    $message = "DO NOT REPLY\n============\nHello $first_name, You have an outstanding bill:\nInvoice Name::\n$invoice_name\n$$bid_price that was billed on $invoice_date. You can use one of the following links to access it:\n\n$link1\n\n$link2\n\n$link3\nYou will be prompted to sign up for the application. Once signed up, you will be taken to the invoice where you can scroll down and pay.\n\nIf you are not taken to the invoice:\n1. Select Jobs at the bottom of the application.\n2. Select approved at the top of the page.\n3. Select the blue box to view the invoice.\n4. Select 'Pay with Credit Card' at the bottom of the invoice.\n5. Enter your credit card information to make payment.\n\nPlease reach out to Shawn if you think that this is an error or you have any questions: 480-703-4902 or 480-622-6441.";
//                }

//                $message = "Hi $first_name, just a friendly reminder about your bill for:\nInvoice Name::\n$invoice_name\n$$bid_price that was billed on $invoice_date. You can use one of the following links to access it:\n\n$link1\n\n$link2\n\n$link3\nYour prompt payment would be much appreciated.\nPlease reach out to Shawn if you think that this is an error or you have any questions at 480-703-4902 or 480-622-6441.\nHappy holidays from KPS POOLS LLC!";

                $message = self::paymentReminder(
                    $bid_price,
                    $first_name,
                    $last_name,
                    $invoice_name,
                    $invoice_date,
                    $signedUpStatus,
                    $link1,
                    $link2,
                    $link3
                );


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


//    public function test_sending_Holiday_Notification()
//    {
//        $f = @fopen('/Users/shawnpike/Documents/code/business/kpspools/tests/Feature/Notifications/phone_numbers.csv', 'r');
//        if ($f) {
//            while (($buffer = fgets($f, 4096)) !== false) {
//                $line = explode(',', $buffer);
//                $message = self::secondHolidayMessage();
//                $this->sendOneNumber($line[0], $message);
//            }
//        }
//    }


    private function paymentReminder(
        $bid_price,
        $first_name,
        $last_name,
        $invoice_name,
        $invoice_date,
        $signedUpStatus,
        $link1,
        $link2,
        $link3
    ): string
    {
//        return "Hello $first_name $last_name!\n\nThis is a friendly reminder about your past due bill.\n\n$invoice_name was invoiced on $invoice_date for $$bid_price.\n\nTo settle it conveniently, you can use the following links to make a payment::\n\n$link1\n\n$link2\n\n$link3\n\nWould you like to set up autopay for future bills? Reply with:\n\n* 'Yes' for autopay\n* 'No' if you prefer not to use autopay";
        return "Hello $first_name $last_name!\n\nThis is a friendly reminder about your current bill.\n\n$invoice_name was invoiced on $invoice_date for $$bid_price.\n\nTo settle it conveniently, you can use the following links to make a payment::\n\n$link1\n\n$link2\n\n$link3\n\nWould you like to set up autopay for future bills? Reply with:\n\n* 'Yes' for autopay\n* 'No' if you prefer not to use autopay";
    }

    private function secondHolidayMessage(): string
    {
        return "As we embrace the holiday season, we wanted to remind you that our team will be taking our second well-deserved break for the next two weeks. During this time, our services will be temporarily paused.\n\nRest assured, if you encounter any urgent issues or require assistance, please don't hesitate to reach out to us directly. We remain committed to ensuring your satisfaction and will respond promptly.\n\nWe extend our warmest wishes to you and yours for a joyous holiday season and a prosperous New Year ahead. Thank you for your continued support and understanding.";
    }

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

    private function sendOneNumber($num, $message)
    {
        Notification::route('vonage', $num)
            ->notify(new MassTextNotification($message));
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

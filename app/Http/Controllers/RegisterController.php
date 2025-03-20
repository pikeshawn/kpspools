<?php

namespace App\Http\Controllers;

use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class RegisterController extends Controller
{
    //
    public function registerLink(Request $request)
    {
        //        dd($request->phoneNumber);
        $phoneNumber = self::validatePhoneNumber($request);

        if ($phoneNumber !== '') {
            $message = "Thank you for your interest in KPS Pools. Please use the link below to register and we will setup an appointment with you promptly.\n\n https://kpspools.com/register";
            Notification::route('vonage', $phoneNumber)->notify(
                new GenericNotification($message));
        }
    }

    private function validatePhoneNumber($request): string
    {
        $phone = $request->phoneNumber;
        if (strlen($phone) === 10) {
            return '1'.$phone;
        } elseif (strlen($phone) === 11) {
            return $phone;
        }

        return '';
    }
}

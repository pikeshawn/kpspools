<?php

namespace App\Http\Controllers;

use App\Notifications\MassTextNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    //

    public function notification()
    {
        return Inertia::render('Notification/Index');
    }

    public function generic(Request $request)
    {

        $messageArray = explode('$param', $request->message);
        $lines = explode("\n", $request->data);

        foreach ($lines as $line) {

            $elements = explode(',', $line);

            $constructedMessage = '';
            for ($i = 0; $i < count($elements) - 1; $i++) {
                $constructedMessage = $constructedMessage . $messageArray[$i] . $elements[$i];
            }

            $constructedMessage = $constructedMessage . $messageArray[count($elements) - 1];

            Notification::route('vonage', $elements[count($elements) - 1])
                ->notify(new MassTextNotification($constructedMessage));

        }


        $finished = 'finished';

    }

}

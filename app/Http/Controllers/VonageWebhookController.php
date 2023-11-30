<?php

namespace App\Http\Controllers;

use App\Notifications\InboundMessageNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vonage\Response;
use App\Models\InboundMessage;
use App\Models\Customer;
use function Psy\debug;

class VonageWebhookController extends Controller
{
    //

    public function inbound(Request $request)
    {
        $fullMessage = self::createMessage($request);
        $customer = Customer::where('phone_number', $request['msisdn'])->first();
//        Log::debug($customer);
        self::storeMessage($request, $fullMessage, $customer);

        $customerName = $customer[0]->first_name . " " . $customer[0]->last_name;

        Notification::route('vonage', '14806226441')
            ->notify(new InboundMessageNotification($customerName, $request['text'], $request['msisdn']));

        return response()->json([], 204);
    }

    public function delivery(Request $request)
    {
        Log::debug($request);
        return response()->json([],204);
    }

    private function createMessage(Request $request): array
    {
        $fullMessage = [];
        $fullMessage['msisdn'] = $request['msisdn'];
        $fullMessage['to'] = $request['to'];
        $fullMessage['messageId'] = $request['messageId'];
        $fullMessage['text'] = $request['text'];
        $fullMessage['type'] = $request['type'];
        $fullMessage['api-key'] = $request['api-key'];
        $fullMessage['message-timestamp'] = $request['message-timestamp'];

        return $fullMessage;
    }

    private function storeMessage(Request $request, $fullMessage, $customer)
    {
        $inboundMessage = new InboundMessage();
        $inboundMessage->customer_id = $customer[0]->id;
        $inboundMessage->text = $request['text'];
        $inboundMessage->message = json_encode($fullMessage);
        $inboundMessage->save();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use App\Notifications\InboundMessageNotification;
use App\Notifications\GenericNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vonage\Response;
use App\Models\InboundMessage;
use App\Models\Customer;
use App\Models\Task;
use function Psy\debug;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class VonageWebhookController extends Controller
{
    //

    protected Customer $customer;
    protected int $taskNumber;
    protected string $answer;

    public function inbound(Request $request): JsonResponse
    {


//        Notification::route('vonage', $request['msisdn'])->notify(new GenericNotification($request['msisdn']));

//        log::debug('phone number:: ' . $request['msisdn']);
        log::debug($request);
        $customer = Customer::where('phone_number', $request['msisdn'])->first();
        if ($customer) {
            $this->customer = $customer;
            self::approvalResponse($request) ? self::updateStatus($request) : self::notifyAdmin($request);
        } else {
            return response()->json([], 204);
        }
        return response()->json([], 204);
    }

    private function approvalResponse($request)
    {
        $answer = str_split($request['text']);
        $taskNumber = substr($request['text'], 1);

        if (!ctype_digit((string)$taskNumber)) return false;

        $this->taskNumber = $taskNumber;
        $this->answer = $answer[0];

//        Notification::route('vonage', $request['msisdn'])->notify(new GenericNotification($taskNumber));

        return (strtoupper($this->answer) == 'N' || strtoupper($this->answer) == 'Y') && ctype_digit((string)$this->taskNumber);
    }

    private function updateStatus($request)
    {
        $task = Task::where('customer_id', $this->customer->id)->where('count', $this->taskNumber)->first();
        Log::debug($task);
        Log::debug('Customer Id:: ' . $this->customer->id);
        Log::debug('taskNumber:: ' . $this->taskNumber);

        if ($task) {
            if (strtoupper($this->answer) == 'N') {
                $task->status = 'denied';
                Notification::route('vonage', $request['msisdn'])->notify(new GenericNotification('Thank you for response. We will remove this task from the list.'));
            } else if (strtoupper($this->answer) == 'Y') {
                $task->status = 'approved';
                Notification::route('vonage', $request['msisdn'])->notify(new GenericNotification('Thank you for response. We will get you scheduled and complete the work as soon as possible'));
            }
            $task->save();
            $date = Carbon::now();
            $statusDate = $date->format('Y-m-d H:i:s');
            $taskStatus = new TaskStatus([
                'status_creator' => $this->customer->user_id,
                'task_id' => $task->id,
                'status' => $task->status,
                'status_date' => $statusDate
            ]);
            $taskStatus->save();
        } else {
            Notification::route('vonage', $request['msisdn'])->notify(new GenericNotification('We can not find that task. Can you please retype your response to match the text above or reach out to Shawn at 480.703.4902 or 480.622.6441 if you have any questions.'));
        }

    }

    private function notifyAdmin($request)
    {
        $fullMessage = self::createMessage($request);
        if ($this->customer) {
            self::storeMessage($request, $fullMessage, $this->customer);
            $customerName = $this->customer->first_name . " " . $this->customer->last_name;
            Notification::route('vonage', '14806226441')->notify(new InboundMessageNotification($customerName, $request['text'], $request['msisdn']));
        }
    }

    public function delivery(Request $request)
    {
        Log::debug($request);
        return response()->json([], 204);
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
        $inboundMessage->customer_id = $customer->id;
        $inboundMessage->text = $request['text'];
        $inboundMessage->message = json_encode($fullMessage);
        $inboundMessage->save();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use GuzzleHttp\Client;
use App\Models\Chat;

class ChatController extends Controller
{
    //

    public function chatResponse(Request $request)
    {

     $m = self::getMessage($request);

        return Inertia::render('Chat/Response', [
            'response' => $m
        ]);
    }

    public function chat()
    {
        $chats = Chat::all(['id', 'question', 'user_id']);

        return Inertia::render('Chat/Index', [
            'chat' => $chats
        ]);
    }

    public function chatQuestion(Chat $chat)
    {
        return Inertia::render('Chat/Question', [
            'chat' => $chat
        ]);
    }

    private function getMessage($request)
    {
        $message = self::getContent($request);

        $m = self::processMessage($message);

        self::saveResponse($request, $m);

        return str_replace("\n", "", $m);

    }

    private function processMessage($message)
    {
        $m = $message['choices'][0]['message']['content'];
        $startTag = strpos($m, "html");

        if($startTag){
            $endTag = strpos($m, "</html>");
            $stringLength = strlen($m);
            return substr($m, $startTag + 4, $stringLength-$startTag-($stringLength-($endTag+7)));
        }

        return $m;
    }

    private function saveResponse($request, $m)
    {
        Chat::firstOrCreate([
            'question' => $request->message,
            'answer' => $m,
            'user_id' => $request->user
        ]);
    }

    private function getContent($request)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPEN_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'A service technician that does manual labor. Format it as HTML and style with Tailwind classes'
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->message
                    ]
                ],
                'model' => 'gpt-4-turbo',
                "temperature" => 0.7
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

}

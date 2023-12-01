<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    //

    public function chatResponse(Request $request)
    {

//        dd($request);

//        $m = "<title>Steps to Replace Sand in a Sand Filter</title></head><body>  <h1>Steps to Replace Sand in a Sand Filter</h1>  <ol>    <li>Turn off the pool pump and close the valves to stop the flow of water.</li>    <li>Release the pressure in the filter system by opening the air relief valve.</li>    <li>Remove the filter cover or lid by following the manufacturer's instructions.</li>    <li>Use a pool filter sand scoop or a small bucket to carefully remove the old filter sand from the filter tank.</li>    <li>Inspect the filter laterals or fingers for any damage and replace them if necessary.</li>    <li>Thoroughly clean the inside of the filter tank using a hose or pressure washer if needed.</li>    <li>Prepare the new filter sand by following the manufacturer's instructions regarding the type and quantity of sand required.</li>    <li>Pour the new filter sand into the filter tank, making sure to distribute it evenly and not damage the laterals.</li>    <li>Reinstall the filter cover or lid securely.</li>    <li>Open the air relief valve briefly to release any trapped air, then close it.</li>    <li>Open the valves and turn on the pool pump to resume normal filtration.</li>    <li>Check for any leaks or unusual noises, and make necessary adjustments if required.</li>    <li>Regularly backwash the filter to remove any impurities and keep it functioning optimally.</li>";

        $m = self::getMessage($request);

//        dd($m);

        return Inertia::render('Chat/Index', [
            'response' => $m
        ]);

//        return Inertia::
//        return response()->json(['message' => $response->getBody()->getContents()]);
    }

    public function chat()
    {
        return Inertia::render('Chat/Index');
    }

    private function getMessage($request)
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
                'model' => 'gpt-3.5-turbo',
                "temperature" => 0.7
            ]
        ]);

        $message = json_decode($response->getBody()->getContents(), true);

        $m = $message['choices'][0]['message']['content'];
        return str_replace("\n", "", $m);
    }
}

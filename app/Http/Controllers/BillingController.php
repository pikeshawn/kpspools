<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BillingController extends Controller
{
    //

    public function setup()
    {
        return Inertia::render('Billing/Setup');
    }

    //
    /**
     * Show the form for creating a new resource.
     */
    public function unpaid(): Response
    {

        $response = self::submitToJemmson();

        $jsonResponse = json_decode($response);

//        dd($jsonResponse);

        return Inertia::render('Billing/Unpaid', [
            'unpaid' => $jsonResponse
        ]);
    }

    private static function submitToJemmson()
    {
        $client = new Client();

        $response = $client->post(env('API_URL') . '/unpaid', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ]
        ]);

        return $response->getBody()->getContents();
    }
}

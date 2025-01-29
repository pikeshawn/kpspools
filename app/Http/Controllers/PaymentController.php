<?php

namespace App\Http\Controllers;

use App\Models\Address;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    //
    /**
     * Show the form for creating a new resource.
     */
    public function index(Address $address): Response
    {

        $customer = Customer::find($address->customer_id);

        $response = self::submitToJemmson($customer->jemmson_id);
        $jsonResponse = json_decode($response);

        return Inertia::render('Payments/Index', [
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
            'history' => $jsonResponse
        ]);
    }

    private static function submitToJemmson($jemmsonId)
    {
        $client = new Client();

        $response = $client->post(env('API_URL') . '/payment-history', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('BEARER_TOKEN'),
                'Accept' => 'application/json',
            ],
            'json' => [
                "jemmsonId" => $jemmsonId
            ]
        ]);

        return $response->getBody()->getContents();
    }

}

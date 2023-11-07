<?php

namespace App\Http\Controllers;

use App\Http\Services\MyFatoorahServices;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class MyFatoorahController extends Controller
{
    private $base_url;
    private $headers;
    private $request_client;
    private $fatoorahServices;

    function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->base_url = env('myfatoorah_base_url');
        $this->headers = [
            'ContentType' => 'application/json',
            'authorization' => 'Bearer' . env('myfatoorah_token'),
        ];
    }

    function payOrder()
    {
        $data = [
            "CustomerName" => 'mohamed',
            "NotificationOption" => "LNK",
            "InvoiceValue" =>   100,
            "CustomerEmail" => "mohamed.g1501@gmail.com",
            "CallBackUrl" => env('myfatoorah_success_url'),
            "ErrorUrl" => env('myfatoorah_error_url'),
            "Language" => "en",
            "DisplayCurrencyIso" => "SAR",
        ];

        return  $this->sendPayment($data);
    }


    private function establishConnection($uri, $method, $data = [])
    {
        $request = new Request($method, env('myfatoorah_base_url') . $uri, $this->headers);

        // if ($data) {
        //     return false;
        // }

        $response = $this->request_client->send($request, ['json' => $data]);

        // if ($response->getStatusCode() != 200) {
        //     return false;
        // }

        $response = json_decode($response->getBody(), true);
        return $response;
    }


    public function sendPayment($request_data)
    {
        return  $response = $this->establishConnection('/v2/sendPayment', 'POST', $request_data);
    }
}

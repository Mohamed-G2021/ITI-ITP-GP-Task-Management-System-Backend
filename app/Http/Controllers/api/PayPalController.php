<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{

    public function index()
    {
        return redirect(env('FRONTEND_DOMAIN') . '/paypal');
    }

    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment/cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "100.00"
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {

            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect(env('FRONTEND_DOMAIN') . '/payment-failed');
        } else {
            return redirect(env('FRONTEND_DOMAIN') . '/payment-failed');
        }
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['error']['name'])) {
            $user =  User::find(Auth::id());
            $user->update(['subscribed' => 'yes']);

            return redirect(env('FRONTEND_DOMAIN') . '/payment-success');
        } else {
            return redirect(env('FRONTEND_DOMAIN') . '/payment-failed');
        }
    }

    public function paymentCancel()
    {
        return redirect(env('FRONTEND_DOMAIN') . '/payment-failed');
    }
}

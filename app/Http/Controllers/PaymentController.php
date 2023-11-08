<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // authorization token request from safaricom
    public function token()
    {
        $consumerKey = 'JMH2I4ozADWQ5J0hg3jez1yne9EAGcff';
        $consumerSecret = 'PsRkfqH6ALW7NB6V';
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get($url);
        
        // return $response->json();

        // $token = $response->json()['access_token'];
        // return $token;
        return $response['access_token'];
        
    }

    public function initiateStkPush()
    // launch the stk push on the user's phone
    {
        $accessToken = $this->token();
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $passKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $BusinessShortCode = 174379;
        $Timestamp = Carbon::now()->format('YmdHis');
        $Password = base64_encode($BusinessShortCode.$passKey.$Timestamp);
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = 1;
        $PartyA = 254743168819;
        $PartyB = 174379;
        $PhoneNumber = 254743168819;
        $CallBackURL = 'https://7067-102-212-236-130.ngrok-free.app/payments/stkcallback';
        $AccountReference = 'Tripuo Verse';
        $TransactionDesc = 'Tripuo Verse Payment for Order';
        $response = Http::withToken($accessToken)
            ->post($url, [
                'BusinessShortCode' => $BusinessShortCode,
                'Password' => $Password,
                'Timestamp' => $Timestamp,
                'TransactionType' => $TransactionType,
                'Amount' => $Amount,
                'PartyA' => $PartyA,
                'PartyB' => $PartyB,
                'PhoneNumber' => $PhoneNumber,
                'CallBackURL' => $CallBackURL,
                'AccountReference' => $AccountReference,
                'TransactionDesc' => $TransactionDesc,
            ]);

        // return $response->json();
        return $response;
    }

    public function stkCallback()
    {
        dd('Reached stkCallback');
        $data = file_get_contents('php://input');
        Storage::disk('local')->put('stk.txt', $data);
    }

    public function processStkPush()
    {

    }
}

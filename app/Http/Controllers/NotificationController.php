<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Vonage\Client;
use \Vonage\Client\Credentials\Basic;

class NotificationController extends Controller
{

    public function sendSmsNotificaition($phone, $otp)
    {
        if (!empty($phone)) {
            $basic  = new Basic('888c7438', 'GIz4cOabMRhoTlg5');
            $client = new Client($basic);

            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS($phone, 'Widget Hubs', 'Your One time otp is ' . $otp . ' valid for 10 mints')
            );
            
            $message = $response->current();

            if ($message->getStatus() == 0) {
                return "The message was sent successfully";
            } else {
                return "The message failed with status: " . $message->getStatus();
            }
        }
    }
}

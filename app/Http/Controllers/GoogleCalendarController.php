<?php

namespace App\Http\Controllers;

use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GoogleCalendarController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Client();

        $clientId = Config::get('google-calendar-api.client_id');
        $clientSecret = Config::get('google-calendar-api.client_secret');
        $redirectUrl = Config::get('google-calendar-api.redirect_url');
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUrl);

        $client->addScope(\Google\Service\Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $clientId = Config::get('google-calendar-api.client_id');
        $clientSecret = Config::get('google-calendar-api.client_secret');
        $redirectUrl = Config::get('google-calendar-api.redirect_url');

        $client = new Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUrl);

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        session(['access_token' => $token]);
        return response()->json($token);
    }
}

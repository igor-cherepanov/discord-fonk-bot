<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as HttpClient;
use NotificationChannels\Discord\Discord;
use NotificationChannels\Discord\DiscordMessage;

class HomeController extends Controller
{

    public function index(){
        $guzzleClient = new HttpClient();
        DiscordMessage::create('test');
    }

}

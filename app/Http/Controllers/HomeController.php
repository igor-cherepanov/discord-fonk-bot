<?php

namespace App\Http\Controllers;


use Discord\Discord;

class HomeController extends Controller
{

    /**
     * @throws \Discord\Exceptions\IntentException
     */
    public function index()
    {
        $discord = new Discord([
            'token' => env('DISCORD_TOKEN'),
        ]);

        $discord->on('ready', function ($discord) {
            echo "Bot is ready!", PHP_EOL;

            // Listen for messages.
            $discord->on('message', function ($message, $discord) {
                echo "{$message->author->username}: {$message->content}", PHP_EOL;
            });
        });

    }

}

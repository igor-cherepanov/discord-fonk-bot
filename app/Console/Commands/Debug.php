<?php

namespace App\Console\Commands;

use Discord\Discord;
use Illuminate\Console\Command;

class Debug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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

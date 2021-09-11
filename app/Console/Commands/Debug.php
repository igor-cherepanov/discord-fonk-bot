<?php

namespace App\Console\Commands;

use App\Models\BadZetsu;
use App\Models\TrueSite;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
     * @throws \Discord\Exceptions\IntentException
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
                /** @var \Discord\Parts\Channel\Message $message */
                $messageContent = $message->content;
                $senderUserName = $message->author->username;
                echo "{$senderUserName}: {$messageContent}", PHP_EOL;

                if (\Illuminate\Support\Str::contains($messageContent, 'Бот')) {
                    $this->say($message);
                }
                if (\Illuminate\Support\Str::contains($messageContent, 'stat')) {
                    $this->stat($message);
                }
                if (\Illuminate\Support\Str::contains($messageContent, 'test')) {
                    $this->test($message);
                }

            });
        });

        $discord->run();


    }

    /**
     * @param \Discord\Parts\Channel\Message $message
     */
    public function say(\Discord\Parts\Channel\Message $message): void
    {
        $messageContent = $message->content;
        $senderUserName = $message->author->username;
        $senderUserChannel = $message->channel;
        $clearMessage = \Illuminate\Support\Str::replace('Бот', '', $messageContent);
        $newMessage = $senderUserName . ' сам ты ' . $clearMessage;
        $senderUserChannel->sendMessage($newMessage);
        $senderUserChannel->sendMessage('И ваще ты' . PHP_EOL . BadZetsu::getRandBadPhrase());
    }

    /**
     * @param \Discord\Parts\Channel\Message $message
     */
    public function stat(\Discord\Parts\Channel\Message $message): void
    {
        $messageContent = $message->content;
        $senderUserChannel = $message->channel;
        $newMessage = "Ник не указан";
        $dirtyNickName = Arr::get(explode('stat', $messageContent), 1);
        if ($dirtyNickName !== null) {
            $nickName = Arr::get(explode(' ', $dirtyNickName), 1);
            if ($nickName !== null) {
                $newMessage = TrueSite::getPvpStat($nickName);
            }
        }

//        $newMessages = Str::s($newMessage, 2000);
//        foreach ($newMessages as $message){
        $senderUserChannel->sendMessage(substr($newMessage, 0, 1000));
//        }

    }

    public function test(\Discord\Parts\Channel\Message $message)
    {
        $messageContent = $message->content;
        $senderUserChannel = $message->channel;


        $content = "Hello World! This is message line ;) And here is the mention, use userID <@12341234123412341>";
        $username = "krasin.space";
        $avatarUrl = "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=512";
        $embeds = [
            [
                "title" => "Сатрити какое саабщение",
                "type" => "rich",
                "description" => "Крутое",
                "url" => "https://github.com/igor-cherepanov",
                "color" => hexdec("3366ff"),
//                "footer" => [
//                    "text" => "GitHub.com/Mo45",
//                    "icon_url" => "https://ru.gravatar.com/userimage/28503754/1168e2bddca84fec2a63addb348c571d.jpg?size=375"
//                ],
                "image" => [
                    "url" => "http://img10.reactor.cc/pics/post/%D0%B0%D0%BD%D0%B8%D0%BC%D0%B5-%D1%8D%D1%80%D0%B3%D0%BE-%D0%BF%D1%80%D0%BE%D0%BA%D1%81%D0%B8-%D1%80%D0%B8%D0%BB-%D0%BC%D0%B5%D0%B9%D0%B5%D1%80-Re-l-Mayer-1270696.jpeg"
                ],
                "thumbnail" => [
                    "url" => "http://img10.reactor.cc/pics/post/%D0%B0%D0%BD%D0%B8%D0%BC%D0%B5-%D1%8D%D1%80%D0%B3%D0%BE-%D0%BF%D1%80%D0%BE%D0%BA%D1%81%D0%B8-%D1%80%D0%B8%D0%BB-%D0%BC%D0%B5%D0%B9%D0%B5%D1%80-Re-l-Mayer-1270696.jpeg"
                ],
                "author" => [
                    "name" => "ИГАРЕХА",
                    "url" => "https://github.com/igor-cherepanov"
                ],
                "fields" => [
                    [
                        "name" => "Пасасите",
                        "value" => "Раз",
                        "inline" => false
                    ],
                    [
                        "name" => "Пасасити",
                        "value" => "Два",
                        "inline" => true
                    ]
                ]
            ]

        ];

        $senderUserChannel->sendMessage(MessageBuilder::new()
            ->setContent('Test')
            ->setEmbeds($embeds)
            ->setTts(true)

        );

    }

}

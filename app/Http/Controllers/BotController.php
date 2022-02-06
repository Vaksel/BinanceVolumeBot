<?php

namespace App\Http\Controllers;

use App\Models\Botman;
use App\Models\BotUsers;
use App\Models\VolumeCompare;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Http\Request;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Support\Facades\Cache;
use WebSocket\Client;

class BotController extends Controller
{
    public const VOLUME_BOT_TOKEN = '1823777279:AAFhFT7eguIkVdFHB4OngWEe6pTMNI0wVas';
    public const PERCENTS_BOT_TOKEN = '1823777279:AAFhFT7eguIkVdFHB4OngWEe6pTMNI0wVas';
    public const ALL_PAIRS_PERCENTS_BOT_TOKEN = '1823777279:AAFhFT7eguIkVdFHB4OngWEe6pTMNI0wVas';


    public static function getConstants(): array
    {
        return [
            'TELEGRAM_TOKEN' => '1823777279:AAFhFT7eguIkVdFHB4OngWEe6pTMNI0wVas'
        ];
    }

    public function testSocket()
    {
        VolumeCompare::volumeCompareStart();

        while (count($infoArr) < 10) {
            try {
                $infoArr[] = $client->receive();
                // Act on received message
                // Break while loop to stop listening
            } catch (\WebSocket\ConnectionException $e) {
                ddd($e);
            }
        }

        ddd($infoArr);
    }

    public function runVolume()
    {
        $botman = $this->getBotmanInstance([
            'telegram' => [
                'token' => self::VOLUME_BOT_TOKEN
            ]
        ]);

        $storage = $botman->userStorage();

        $botman->hears('/start', function ($bot)
        {
            $user = $bot->getUser();

            $bot->userStorage()->save([
                'telegram_id' => $user->getId()
            ]);

            $bot->say('Приветствую', $user->getId(), TelegramDriver::class);

            $bot->reply('Вы успешно записались на рассылку сигналов с помощью бота, осталось только выбрать нужный профиль для отслеживания по ссылке https://binance-test.orendaherbal.com/');

            $bot->reply('После того как определитесь с нужным профилем, возвращайтесь сюда и отправьте мне желаемый номер профиля, я буду ждать)');

            $bot->reply('Ах да, чуть было не забыл, для получения помощи отправьте команду /help.');

            $bot->say('Введите номер профиля при помощи отправки сообщения вида: Добавить профиль:12', $user->getId(), TelegramDriver::class);
        });

        $botman->hears('/help', function ($bot)
        {
            $bot->reply('Для добавления подписки на профиль отправьте сообщение вида: Добавить профиль:12');

            $bot->reply('Для удаления подписки на профиль отправьте сообщение вида: Удалить профиль:12');

            $bot->reply('Для того чтобы остановить рассылку отправьте сообщение вида: Остановить отправку на профиле:12');

            $bot->reply('Для того чтобы возобновить рассылку отправьте сообщение вида: Возобновить отправку на профиле:12');
        });


        $botman->hears('Добавить профиль:([0-9]+)', function ($bot, $profile_id)
        {
            $user = $bot->getUser();

            $botman_id = Botman::where(['option_name' => 'VOLUME_BOT_TOKEN', 'is_active' => true])->select('id')->first()->id;

            $follow = new BotUsers();
            $follow->telegram_id    = $user->getId();
            $follow->profile_id     = $profile_id;
            $follow->botman_id      = $botman_id;
            $follow->is_active      = true;
            $follow->save();

            $bot->reply('Профиль записан, ожидайте прихода сигналов');
        });

        $botman->hears('Удалить профиль:([0-9]+)', function ($bot, $profile_id)
        {
            $user = $bot->getUser();

            $botman_id = Botman::where(['option_name' => 'VOLUME_BOT_TOKEN', 'is_active' => true])->select('id')->first()->id;

            $follow = new BotUsers();
            $follow->telegram_id    = $user->getId();
            $follow->profile_id     = $profile_id;
            $follow->botman_id      = $botman_id;
            $follow->is_active      = true;
            $follow->save();

            $bot->reply('Профиль записан, ожидайте прихода сигналов');

            $bot->reply('Профиль удален');
        });

        $botman->hears('Приостановить отправку на профиле:([0-9]+)', function ($bot, $profile_id)
        {
            $bot->reply('Отправка по заданному профилю приостановлена');
        });

        $botman->hears('Возобновить отправку на профиле:([0-9]+)', function ($bot, $profile_id)
        {
            $bot->reply('Отправка возобновлена, ожидайте сигналы');
        });

        $botman->listen();
    }

    public function runPercents()
    {
        $botman = $this->getBotmanInstance([
            'telegram' => [
                'token' => self::PERCENTS_BOT_TOKEN
            ]
        ]);

        $botman->hears('/start', function ($bot) {
            $user = $bot->getUser();
            $bot->reply($user->getId());
        });

        $botman->say('Hello', '382142310', TelegramDriver::class);

        $botman->listen();
    }

    public function runAllPairsPercents()
    {
        $botman = $this->getBotmanInstance([
            'telegram' => [
                'token' => self::ALL_PAIRS_PERCENTS_BOT_TOKEN
            ]
        ]);

        $botman->hears('/start', function ($bot) {
            $user = $bot->getUser();
            $bot->reply($user->getId());
        });

        $botman->say('Hello', '382142310', TelegramDriver::class);

        $botman->listen();
    }

    protected function getBotmanInstance($config_properties)
    {
        DriverManager::loadDriver(TelegramDriver::class);

        $config = $config_properties;

        return BotManFactory::create($config);
    }

    public function registerUrlInTelegram($token = self::VOLUME_BOT_TOKEN)
    {
        $body = http_build_query([
            'url' => 'https://binance-test.orendaherbal.com/botVolumeListen',
        ]);

        $url = "https://api.telegram.org/bot{$token}/setWebhook";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content - Type' => 'application / x - www - form - urlencoded; charset = UTF - 8',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        ddd($response);
    }
}

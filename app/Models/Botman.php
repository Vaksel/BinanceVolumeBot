<?php

namespace App\Models;

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

/**
 * TODO: 22.06.2021
 * TODO: продумать что будет внутри модели и заполнить миграцию
 * TODO: пока по мыслям - хранить настройки для ботов, сделать отдельную модель для юзеров
 * Class Botman
 *
 * @package App\Models
 */
class Botman extends Model
{
    use HasFactory;

    protected $table = 'botmans';

    public $timestamps = false;

    public const VOLUME_BOT_TOKEN = '1823777279:AAFhFT7eguIkVdFHB4OngWEe6pTMNI0wVas';
    public const PERCENTS_BOT_TOKEN = '1823777279:AAFhFT7eguIkVdFHB4OngWEe6pTMNI0wVas';
    public const ALL_PAIRS_PERCENTS_BOT_TOKEN = '1823777279:AAFhFT7eguIkVdFHB4OngWEe6pTMNI0wVas';

    protected static function getBotmanInstance($config_properties)
    {
        DriverManager::loadDriver(TelegramDriver::class);

        $config = $config_properties;

        return BotManFactory::create($config);
    }


    /**
     * TODO: 22.06.2021
     * TODO: продумать что делать с сообщением!
     * TODO: пока есть мысль создать свой класс для сообщения и в нем прописать различные методы для трансформации и правильного отображения
     * @param $msg string строка с сообщением, тестовое решение
     * @param $additionalData array всякого рода полезная информация по типу id пользователя, кастомный токен для бота, кастомное сообщение
     * @throws \BotMan\BotMan\Exceptions\Base\BotManException
     */
    public static function sendSingleMessage(string $msg, array $additionalData)
    {
        try
        {
            $botman = self::getBotmanInstance([
                'telegram' => [
                    'token' => self::VOLUME_BOT_TOKEN
                ]
            ]);

            $botman->say($msg, $additionalData['recipient_id'], TelegramDriver::class);

            $botman->listen();

            return ['status' => 'success', 'msg' => 'Сообщение отправлено'];
        }
        catch(Exception $e)
        {
            return ['status' => 'error', 'msg' => 'При отправке сообщения случилась ошибка', 'error' => $e];
        }

    }

    public function setTelegramUserOptions()
    {

    }

    public function botUsers()
    {
        return $this->hasMany('App\Models\BotUsers');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\TelegramSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramCommand extends Command
{
    const TELEGRAM_ADDR = 'https://api.telegram.org/bot';
    /**
     * Создаем команду, для ее вызова нужно будет написать в консоле
     * php artisan command:telegram
     *
     * А если мы захотим записать команды для бота, то вызвать команду:
     * php artisan command:telegram --setCommand
     *
     * @var string
     */
    protected $signature = 'command:telegram {--setCommand}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram get and send message. And set command';

    /**
     * Здесь исполнения нашей команды.
     *
     * @return int
     */
    public function handle()
    {
        $getSetting = TelegramSetting::where('name', 'key')->first();
        if (!$getSetting)
            return $this->output->error("Ошибка выполнения программы!\nСоздайте настройку key с ключем от бота");

        $getAllNewMessages = $this->getUpdates($getSetting['val']);
        if ($getAllNewMessages['status'] > 210)
            return $this->output->error("Ошибка выполнения программы!\nЗапрос выполнен безуспешно!");

        $sendMessage = [];
        foreach ($getAllNewMessages['body']['result'] as $message)
            $sendMessage = $this->parsingMessage($message);

        # Создаем новый массив без пустых элементов
        $sendMessage = array_filter($sendMessage, function ($item){
            return $item != [];
        });

        if (!$sendMessage)
            return  $this->output->success('Нет сообщений для отправки, программа завершена!');

        $this->sendMessages($sendMessage);

        return Command::SUCCESS;
    }

    private function getUpdates($key){
        $response = Http::get(self::TELEGRAM_ADDR . $key . '/getUpdates');
        return ['code' => $response->status(), 'body' => $response->json()];
    }

    private function parsingMessage($message){
        # Получаем идентификатор чата пользователя
        $idUser = $message['message']['from']['id'];

        # Получем текст, который прислал пользователь
        $command = $message['message']['text'];

        # Узнаем, является данный текст командой
        if (!isset($message['message']['entities'])) return [];

        # Вырезаем команду
        $command = mb_substr($command, $message['message']['entities']['offset'], $message['message']['entities']['length']);

        # Получаем все команды из нашего списка
        $commands = TelegramCommand::where('command', $command)->first();

        # Если нет такой команды, ответа не будет
        if (!$commands) return [];

        return ['chat_id' => $idUser, 'text' => $commands->context];
    }

    private function sendMessage($key, $messages){
        foreach($messages as $item){
            $response = Http::post(self::TELEGRAM_ADDR . $key . '/sendMessage', $item)->header('Content_Type: application/json');
        }
    }
}

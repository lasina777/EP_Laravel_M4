<?php

namespace App\Console\Commands;

use App\Models\TelegramSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\TelegramCommand as ModelTelegramCommand;
use Illuminate\Support\Facades\Log;

class TelegramCommand extends Command
{
    const TELEGRAM_ADDR = 'https://api.telegram.org/bot';
    protected $offsetID = 0;
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

        if ($this->option('setCommand')){
            $commands = ModelTelegramCommand::all()->toArray();
            if(!$commands) return $this->output->warning('Нет команд');

            $setCommands = [];
            foreach ($commands as $command){
                $setCommands[] = ['command' => $command['command'], 'description' => 'none'];
            }

            Http::post(self::TELEGRAM_ADDR . $getSetting['val'] . '/setMyCommands', ['commands' => $setCommands]);

            return $this->output->success('Успешно установлены данные команд');
        }

        $getAllNewMessages = $this->getUpdates($getSetting['val']);
        if ($getAllNewMessages['status'] > 210)
            return $this->output->error("Ошибка выполнения программы!\nЗапрос выполнен безуспешно!");

        $sendMessage = [];
        foreach ($getAllNewMessages['body']['result'] as $message)
            $sendMessage[] = $this->parsingMessage($message);

        # Создаем новый массив без пустых элементов
        $sendMessage = array_filter($sendMessage, function ($item){
            return $item != [];
        });
        Log::info('Пришли данные сообщений: ' . json_encode($sendMessage));
        if ($sendMessage == [])
            return  $this->output->success('Нет сообщений для отправки, программа завершена!');

        $this->sendMessage($getSetting['val'], $sendMessage);
        $this->setOffsetId($getSetting['val']);

        return Command::SUCCESS;
    }

    /**
     * Получение входящих обновлений
     *
     * @param $key
     * @return array
     */
    private function getUpdates($key){
        $response = Http::get(self::TELEGRAM_ADDR . $key . '/getUpdates');
        return ['status' => $response->status(), 'body' => $response->json()];
    }

    /**
     * Получение сообщений
     *
     * @param $message
     * @return array
     */
    private function parsingMessage($message){
        # Получаем идентификатор чата пользователя
        $idUser = $message['message']['from']['id'];

        $this->offsetID = $this->offsetID < $message['update_id'] ? $message['update_id'] : $this->offsetID;

        # Получем текст, который прислал пользователь
        $command = $message['message']['text'];

        # Узнаем, является данный текст командой
        if (!isset($message['message']['entities'])) return [];

        # Вырезаем команду
        $commands = [];
        foreach ($message['message']['entities'] as $item)
            $commands[] = mb_substr($command, $item['offset'], $item['length']);

        # Получаем все команды из нашего списка
        $commands = ModelTelegramCommand::whereIn('command', $commands)->get()->toArray();

        # Если нет такой команды, ответа не будет
        if ($commands == []) return [];

        $textContent = '';
        foreach ($commands as $item){
            $textContent .= $item['context'];
        }

        return ['chat_id' => $idUser, 'text' => $textContent];
    }

    /**
     * Отправка сообщений
     *
     * @param $key
     * @param $messages
     * @return void
     */
    private function sendMessage($key, $messages){
        foreach($messages as $item){
            Http::post(self::TELEGRAM_ADDR . $key . '/sendMessage', $item);
            # После каждого каждого сообщения даем отдохнуть запросу на 300 миллисекунд
            usleep(300);
        }
    }

    /**
     * Установка ключа, чтобы сообщения, которые были ранее не загружались
     *
     * @param $key
     * @return void
     */
    private function setOffsetId($key){
        Http::post(self::TELEGRAM_ADDR .$key . '/getUpdates', ['offset' => $this->offsetID++]);
    }
}

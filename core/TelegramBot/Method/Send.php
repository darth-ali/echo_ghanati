<?php

namespace TelegramBot\Method;


use TelegramBot\Launcher;

abstract class Send
{


    private static function __Send(string $functionName, string $slug)
    {

        //        $url = str_replace(' ', '%20', BotLauncher::URL_PREFIX_GENERATOR() . $functionName . "?" . $slug);
        $url = Launcher::URL_PREFIX_GENERATOR() . $functionName . "?" . $slug;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public static function SendMessage($chatID, $text, $parsMode = '', $replyMarkupJson = '')
    {
        $functionName = 'sendMessage';
        $slug[] = 'chat_id=' . $chatID;
        $slug[] = 'text=' . urlencode($text);
        if ($parsMode != '')
            $slug[] = 'parse_mode=' . $parsMode;
        if ($replyMarkupJson != '')
            $slug[] = 'reply_markup=' . $replyMarkupJson;
        $result = self::__Send($functionName, implode('&', $slug));
    }
}
<?php

namespace TelegramBot;

class Launcher
{

    public static function URL_PREFIX_GENERATOR(): string
    {
        $telegramToken = "970903195:AAE2ouuxKtv0HuTQbAhoGJzwC8Dy7SJZXLo";
        return "https://api.telegram.org/bot" . $telegramToken . "/";
    }

}
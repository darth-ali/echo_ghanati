<?php


use TelegramBot\Method\Send;
use TelegramBot\Type\KeyboardButton;
use TelegramBot\Type\ReplyKeyboardMarkup;
use TelegramBot\Type\ReplyKeyboardRemove;
use TelegramBot\Type\Request;
use WP\WPRestAPI;

function start (Request $request)
{
    $button              = KeyboardButton::CREATE('برای احراز هویت کلیک کنید', true);
    $row[]               = $button;
    $keyboard[]          = $row;
    $replyKeyboardMarkup = ReplyKeyboardMarkup::CREATE($keyboard);
    Send::SendMessage($request->getMessage()
                              ->getChat()
                              ->getID(), 'salaaaam', '', $replyKeyboardMarkup->convertToJSON());

}

function authorization (Request $request)
{
    $mobile                   = $request->getMessage()->getContact()->getPhoneNumber();
    $userTelegramChatID       = $request->getMessage()->getContact()->getUserID();
    $data['mobile']           = $mobile;
    $data['telegram_chat_id'] = $userTelegramChatID;

    $a = WPRestAPI::SendRequest('https://tadbirnegar.net/tadbira/api/v1/set_user_telegram_id', 'post', $data);

    $replyKeyboardRemove = ReplyKeyboardRemove::CREATE();
    Send::SendMessage($request->getMessage()->getChat()->getID(), $a, '', $replyKeyboardRemove->convertToJSON());

}

function error ($request)
{
    Send::SendMessage($request->getMessage()->getChat()->getID(), 'دستور نامعتبر است.');
}
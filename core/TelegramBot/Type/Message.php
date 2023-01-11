<?php

namespace TelegramBot\Type;

use SmartDate\SmartDate;

class Message
{
    private string $messageJSON;

    /**
     * TelegramMessage constructor.
     * @param string $messageJSON
     */
    public function __construct(string $messageJSON)
    {
        $this->messageJSON = $messageJSON;
    }

// region Class Getter Functions::

    /**
     * @return int :: Unique message identifier inside this chat
     */
    public function getID(): int
    {
        $result = json_decode($this->messageJSON, false);
        return $result->message_id;
    }

    /**
     * @return User | false Optional. Sender of the message
     */
    public function getFrom()
    {

        $result = json_decode($this->messageJSON, false);
        return (isset($result->from)) ? new User(json_encode($result->from)) : false;

    }

    /**
     * @return SmartDate|false :: Date the message was sent in Unix time
     */
    public function getDate()
    {
        $result = json_decode($this->messageJSON, false);
        return (isset($result->date)) ? new SmartDate(json_encode($result->date)) : false;

    }

    /**
     * @return Chat | false :: Conversation the message belongs to
     */
    public function getChat()
    {

        $result = json_decode($this->messageJSON, false);
        return (isset($result->chat)) ? new Chat(json_encode($result->chat)) : false;

    }

    /**
     * @return string :: Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters
     */
    public function getText(): string
    {
        $result = json_decode($this->messageJSON, false);
        return (isset($result->text)) ? $result->text : '';

    }

    /**
     * @return Contact | false :: Conversation the message belongs to
     */
    public function getContact()
    {

        $result = json_decode($this->messageJSON, false);
        return (isset($result->contact)) ? new Contact(json_encode($result->contact)) : false;

    }
//endregion

}
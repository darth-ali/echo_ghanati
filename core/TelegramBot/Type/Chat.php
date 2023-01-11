<?php

namespace TelegramBot\Type;

class Chat
{
    private string $chatJSON;

    /**
     * TelegramChat constructor.
     * @param string $chatJSON
     */
    public function __construct(string $chatJSON)
    {
        $this->chatJSON = $chatJSON;
    }

// region Class Getter Functions::

    /**
     * @return int :: Unique identifier for this chat.
     */
    public function getID(): int
    {
        $result = json_decode($this->chatJSON, false);
        return $result->id;
    }

    /**
     * @return string :: Type of chat, can be either “private”, “group”, “supergroup” or “channel”
     */
    public function getType(): string
    {
        $result = json_decode($this->chatJSON, false);
        return (isset($result->type)) ? $result->type : '';
    }

    /**
     * @return string :: Optional. Title, for supergroups, channels and group chats
     */
    public function getTitle(): string
    {
        $result = json_decode($this->chatJSON, false);
        return (isset($result->type)) ? $result->type : '';
    }

    /**
     * @return string :: Optional. Username, for private chats, supergroups and channels if available
     */
    public function getUsername(): string
    {
        $result = json_decode($this->chatJSON, false);
        return (isset($result->username)) ? $result->username : '';
    }

    /**
     * @return string :: Optional. First name of the other party in a private chat
     */
    public function getFirstName(): string
    {
        $result = json_decode($this->chatJSON, false);
        return (isset($result->first_name)) ? $result->first_name : '';
    }

    /**
     * @return string :: Optional. Last name of the other party in a private chat
     */
    public function getLastName(): string
    {
        $result = json_decode($this->chatJSON, false);
        return (isset($result->last_name)) ? $result->last_name : '';
    }
//endregion


}
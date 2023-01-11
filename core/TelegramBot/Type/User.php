<?php

namespace TelegramBot\Type;

class User
{
    private string $userJSON;

    /**
     * TelegramUser constructor.
     * @param string $userJSON
     */
    public function __construct(string $userJSON)
    {
        $this->userJSON = $userJSON;
    }

    /**
     * @return int :: Unique identifier for this user or bot.
     */
    public function getID(): int
    {
        $result = json_decode($this->userJSON, false);
        return $result->id;
    }

    /**
     * @return bool :: True, if this user is a bot
     */
    public function isBot(): bool
    {
        $result = json_decode($this->userJSON, false);
        return $result->is_bot;
    }

    /**
     * @return string :: User's or bot's first name
     */
    public function getFirstName(): string
    {
        $result = json_decode($this->userJSON, false);
        return (isset($result->first_name)) ? $result->first_name : '';
    }

    /**
     * @return string :: Optional. User's or bot's last name
     */
    public function getLastName(): string
    {
        $result = json_decode($this->userJSON, false);
        return (isset($result->last_name)) ? $result->last_name : '';
    }

    /**
     * @return string :: Optional. User's or bot's username
     */
    public function getUsername(): string
    {
        $result = json_decode($this->userJSON, false);
        return (isset($result->username)) ? $result->username : '';
    }

    /**
     * @return string :: Optional. IETF language tag of the user's language
     */
    public function getLanguageCode(): string
    {
        $result = json_decode($this->userJSON, false);
        return (isset($result->language_code)) ? $result->language_code : '';
    }
}
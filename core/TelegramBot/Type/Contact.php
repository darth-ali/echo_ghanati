<?php

namespace TelegramBot\Type;

class Contact
{
    private string $contactJSON;

    /**
     * TelegramContact constructor.
     * @param string $contactJSON
     */
    public function __construct(string $contactJSON)
    {
        $this->contactJSON = $contactJSON;
    }

    /**
     * @return string :: Contact's phone number
     */
    public function getPhoneNumber(): string
    {
        $result = json_decode($this->contactJSON, false);
        return (isset($result->phone_number)) ? $result->phone_number : '';

    }

    /**
     * @return string :: Contact's first name
     */
    public function getFirstName(): string
    {
        $result = json_decode($this->contactJSON, false);
        return (isset($result->first_name)) ? $result->first_name : '';

    }

    /**
     * @return string :: Optional. Contact's last name
     */
    public function getLastName(): string
    {
        $result = json_decode($this->contactJSON, false);
        return (isset($result->last_name)) ? $result->last_name : '';

    }

    /**
     * @return int :: Optional. Contact's user identifier in Telegram.
     */
    public function getUserID(): int
    {
        $result = json_decode($this->contactJSON, false);
        return (isset($result->user_id)) ? $result->user_id : '';

    }
}
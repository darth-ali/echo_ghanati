<?php

namespace TelegramBot\Type;

class KeyboardButton
{

    private string $text;
    private bool $requestContact;
    private bool $requestLocation;

    /**
     * TelegramKeyboardButton constructor.
     * @param string $text
     * @param bool $requestContact
     * @param bool $requestLocation
     */
    private function __construct(string $text, bool $requestContact, bool $requestLocation)
    {
        $this->text = $text;
        $this->requestContact = $requestContact;
        $this->requestLocation = $requestLocation;
    }

// region Class Getter Functions::

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isRequestContact(): bool
    {
        return $this->requestContact;
    }

    /**
     * @return bool
     */
    public function isRequestLocation(): bool
    {
        return $this->requestLocation;
    }
//endregion

// region Class Public Functions::

    public function convertToArray(): array
    {
        return [
            'text' => urlencode($this->getText()),
            'request_contact' => $this->isRequestContact(),
            'request_location' => $this->isRequestLocation(),
        ];
    }
//endregion

// region Class Public Static Functions::

    /**
     * @param string $text :: Text of the button. If none of the optional fields are used, it will be sent as a message when the button is pressed
     * @param bool $requestContact :: Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only.
     * @param bool $requestLocation :: Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only.
     * @return KeyboardButton
     */
    public static function CREATE(string $text, bool $requestContact = false, bool $requestLocation = false): KeyboardButton
    {
        return new self($text, $requestContact, $requestLocation);
    }
//endregion

}
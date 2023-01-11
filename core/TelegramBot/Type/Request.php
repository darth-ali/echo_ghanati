<?php
namespace TelegramBot\Type;


class Request
{

    private string $requestJSON;

    /**
     * TelegramRequest constructor.
     * @param string $requestJSON
     */
    public function __construct(string $requestJSON)
    {
        $this->requestJSON = $requestJSON;
    }

// region Class Getter Functions::

    /**
     * @return int
     */
    public function getUpdateID(): int
    {
        $result = json_decode($this->requestJSON, false);
        return $result->update_id;
    }

    /**
     * @return Message | false
     */
    public function getMessage()
    {
        $result = json_decode($this->requestJSON, false);
        return (isset($result->message)) ? new Message(json_encode($result->message)) : false;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        $result = json_decode($this->requestJSON, false);
        if (isset($result->message)) {
            if (isset($result->message->contact))
                return 'send_contact';
            else
                return $this->getMessage()->getText();

        }

        return 'error';
    }


//endregion


}
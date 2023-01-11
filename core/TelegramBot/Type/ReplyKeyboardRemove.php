<?php

namespace TelegramBot\Type;

class ReplyKeyboardRemove
{
    private bool $removeKeyboard;
    private bool $selective;


    /**
     * TelegramReplyKeyboardRemove constructor.
     * @param bool $removeKeyboard
     * @param bool $selective
     */
    private function __construct(bool $removeKeyboard = true, bool $selective = false)
    {
        $this->removeKeyboard = $removeKeyboard;
        $this->selective = $selective;
    }

// region Class Getter Functions::


    /**
     * @return bool
     */
    public function isRemoveKeyboard(): bool
    {
        return $this->removeKeyboard;
    }


    /**
     * @return bool
     */
    public function isSelective(): bool
    {
        return $this->selective;
    }
//endregion

// region Class Public Functions::
    public function convertToJSON()
    {

        $array = [
            'remove_keyboard' => $this->isRemoveKeyboard(),
            'selective' => $this->isSelective(),
        ];
        return json_encode($array);
    }

//endregion

// region Class Public Static Functions::


    /**
     * @param bool $removeKeyboard
     * @param bool $selective
     * @return ReplyKeyboardRemove
     */
    public static function CREATE(bool $removeKeyboard = true, bool $selective = false): ReplyKeyboardRemove
    {
        return new self($removeKeyboard, $selective);
    }


//endregion


}
<?php

namespace TelegramBot\Type;

class ReplyKeyboardMarkup
{
    private array $keyboard;
    private bool $resizeKeyboard;
    private bool $oneTimeKeyboard;
    private string $inputFieldPlaceholder;
    private bool $selective;

    /**
     * TelegramReplyKeyboardMarkup constructor.
     * @param KeyboardButton[] $keyboard
     * @param bool $resizeKeyboard
     * @param bool $oneTimeKeyboard
     * @param string $inputFieldPlaceholder
     * @param bool $selective
     */
    private function __construct(array $keyboard, bool $resizeKeyboard = true, bool $oneTimeKeyboard = true, string $inputFieldPlaceholder = '', bool $selective = false)
    {
        $this->keyboard = $keyboard;
        $this->resizeKeyboard = $resizeKeyboard;
        $this->oneTimeKeyboard = $oneTimeKeyboard;
        $this->inputFieldPlaceholder = $inputFieldPlaceholder;
        $this->selective = $selective;
    }

// region Class Getter Functions::

    /**
     * @return KeyboardButton[][]
     */
    public function getKeyboard(): array
    {
        return $this->keyboard;
    }

    /**
     * @return bool
     */
    public function isResizeKeyboard(): bool
    {
        return $this->resizeKeyboard;
    }

    /**
     * @return bool
     */
    public function isOneTimeKeyboard(): bool
    {
        return $this->oneTimeKeyboard;
    }

    /**
     * @return string
     */
    public function getInputFieldPlaceholder(): string
    {
        return $this->inputFieldPlaceholder;
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
        $keyboard = [];
        foreach ($this->getKeyboard() as $row) {
            foreach ($row as $button) {

                $keyboard[][] = $button->convertToArray();
            }
        }

        $array = [
            'keyboard' => $keyboard,
            'resize_keyboard' => $this->isResizeKeyboard(),
            'one_time_keyboard' => $this->isOneTimeKeyboard(),
            'input_field_placeholder' => $this->getInputFieldPlaceholder(),
            'selective' => $this->isSelective(),
        ];
        return json_encode($array);
    }

//endregion

// region Class Public Static Functions::

    /**
     * @param array $keyboardRows
     * @param bool $resizeKeyboard
     * @param bool $onTimeKeyboard
     * @param string $inputFieldPlaceholder
     * @param bool $selective
     * @return ReplyKeyboardMarkup
     */
    public static function CREATE(array $keyboardRows, bool $resizeKeyboard = true, bool $onTimeKeyboard = true, string $inputFieldPlaceholder = '', bool $selective = false): ReplyKeyboardMarkup
    {
        return new self($keyboardRows, $resizeKeyboard, $onTimeKeyboard, $inputFieldPlaceholder, $selective);
    }


//endregion


}
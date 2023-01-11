<?php


namespace Notification;

use BaseModel\Status;
use BaseModel\Type;
use SmartDate\SmartDate;
use SmartDB\SmartDB;
use SMS\SMS;
use TelegramBot\Method\Send;
use UserManagement\User;

class Notification
{

    private int    $ID;
    private string $message;
    private int    $userID;
    private int    $typeID;
    private int    $carrierID;
    private int    $statusID;
    private string $date;

    /**
     * Notification constructor.
     * @param int    $ID
     * @param string $message
     * @param int    $userID
     * @param int    $typeID
     * @param int    $carrierID
     * @param int    $statusID
     * @param string $date
     */
    private function __construct (int $ID, string $message, int $userID, int $typeID, int $carrierID, int $statusID, string $date)
    {
        $this->ID        = $ID;
        $this->message   = $message;
        $this->userID    = $userID;
        $this->typeID    = $typeID;
        $this->carrierID = $carrierID;
        $this->statusID  = $statusID;
        $this->date      = $date;
    }

    // region Class Getter Functions::

    /**
     * @return int
     */
    public function getID (): int
    {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getMessage (): string
    {
        return $this->message;
    }

    /**
     * @return User
     */
    public function getUser (): User
    {
        return User::Create($this->userID);
    }

    /**
     * @return Type
     */
    public function getType (): Type
    {
        global $notificationTypes;
        return $notificationTypes[$this->typeID];
    }

    /**
     * @return Type
     */
    public function getCarrier (): Type
    {
        global $notificationCarriers;
        return $notificationCarriers[$this->carrierID];
    }

    /**
     * @return Status
     */
    public function getStatus (): Status
    {
        global $publicStatus;
        return $publicStatus[$this->statusID];
    }

    /**
     * @return SmartDate
     */
    public function getDate (): SmartDate
    {
        return new SmartDate($this->date, 'string');

    }

    //endregion

    // region Class Static Functions::

    /**
     * @param int $ID
     * @return false|Notification
     */
    public static function Create (int $ID)
    {
        $db          = new SmartDB();
        $queryResult = $db->select()->from('wp_notification')->where('ID')->equalTo($ID)->execute();

        if (count($queryResult) > 0) {
            $dbObject = $queryResult[0];
            $date     = ($dbObject->date != NULL) ? $dbObject->date : '';
            return new self($dbObject->ID, $dbObject->message, $dbObject->user_id, $dbObject->type_id, $dbObject->carrier_id, $dbObject->status_id, $date);
        }
        return false;
    }

    /**
     * @param int    $userID
     * @param string $message
     * @param int    $notificationTypeID
     * @param int    $notificationCarrierID
     * @return false|Notification
     */
    private static function Insert (int $userID, string $message, int $notificationTypeID = 1, int $notificationCarrierID = 1)
    {
        $table = 'wp_notification';

        $db = new SmartDB();

        $insertedID = $db->insert($table)
                         ->addData('user_id', $userID, '%d')
                         ->addData('type_id', $notificationTypeID, '%d')
                         ->addData('carrier_id', $notificationCarrierID, '%d')
                         ->addData('status_id', 1, '%d')
                         ->addData('message', $message, '%s')
                         ->addData('date', current_time('mysql'), '%s')
                         ->save();
        return !$insertedID ? false : Notification::Create($insertedID);

    }

    /**
     * @param User   $user
     * @param string $message
     * @param int    $carrierID
     * @param int    $typeID
     * @return false|Notification
     */
    private static function SendMessage (User $user, string $message, int $carrierID = 1, int $typeID = 1)
    {
        $notification = self::Insert($user->getID(), $message, $typeID, $carrierID);

        if ($carrierID == 2) {
            Send::SendMessage($user->getTelegramChatID(), $message);
        }
        if ($carrierID == 1) {
            SMS::SendWelcomeMessage($user);
        }
        return $notification;

    }

    /**
     * @param User $user
     * @param int  $carrierID
     * @return false|Notification
     */
    public static function SendVerificationCode (User $user, int $carrierID = 1)
    {
        $verificationCode = rand(100000, 1000000);
        $notification     = self::Insert($user->getID(), $verificationCode, 2);

        if ($carrierID == 1) {
            SMS::SendVerificationCode($user, $verificationCode);
        }
        elseif ($carrierID == 2) {
            Send::SendMessage($user->getTelegramChatID(), 'کد تایید شما: ' . $verificationCode);
        }
        return $notification;

    }

    /**
     * @param User $user
     * @return false|Notification
     */
    public static function SendWelcomeMessage (User $user)
    {
        $notification = self::Insert($user->getID(), 'پیام خوش آمد به ' . $user->getFullName(), 3);
        SMS::SendWelcomeMessage($user);
        return $notification;
    }

    /**
     * @param User   $user
     * @param string $message
     * @return false|Notification
     */
    public static function SendSystemNotification (User $user, string $message)
    {
        return self::SendMessage($user, $message, 2, 1);
    }

    /**
     * @param int $userID
     * @return Notification[]
     */
    public static function LastVerificationCodesRequestedBy (int $userID): array
    {
        $result = [];

        $db                = new SmartDB();
        $verificationCodes = $db->select()
                                ->from('wp_notification')
                                ->where('type_id')
                                ->equalTo(2)
                                ->and('user_id')
                                ->equalTo($userID)
                                ->and('date')
                                ->recentHours(1)
                                ->execute();

        foreach ($verificationCodes as $notification) {
            $result[] = self::Create($notification->ID);
        }
        return $result;

    }

    /**
     * @param int    $userID
     * @param string $code
     * @return false|Notification
     */
    public static function CheckVerificationCode (int $userID, string $code)
    {

        $db      = new SmartDB();
        $results = $db->select()
                      ->from('wp_notification')
                      ->where('type_id')
                      ->equalTo(2)
                      ->and('user_id')
                      ->equalTo($userID)
                      ->and('message')
                      ->like($code)
                      ->execute();

        if (count($results) > 0)
            return self::Create($results[0]->ID);
        else
            return false;
    }
    //endregion

}
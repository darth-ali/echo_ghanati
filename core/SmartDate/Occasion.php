<?php


namespace SmartDate;

use BaseModel\Type;
use SmartDB\SmartDB;

class Occasion
{
    private int    $ID;
    private int    $day;
    private int    $month;
    private int    $calendarTypeID;
    private int    $typeID;
    private bool   $isHoliday;
    private string $subject;
    private string $detail;

    /**
     * Occasion constructor.
     * @param int $ID
     * @param int $day
     * @param int $month
     * @param int $calendarTypeID
     * @param int $typeID
     * @param bool $isHoliday
     * @param string $subject
     * @param string $detail
     */
    private function __construct(int $ID, int $day, int $month, int $calendarTypeID, int $typeID, bool $isHoliday, string $subject, string $detail = '')
    {
        $this->ID = $ID;
        $this->day = $day;
        $this->month = $month;
        $this->calendarTypeID = $calendarTypeID;
        $this->typeID = $typeID;
        $this->isHoliday = $isHoliday;
        $this->subject = $subject;
        $this->detail = $detail;
    }

    // region Class Getter Functions::

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @return SmartDate | false
     */
    public function getBecomingDate()
    {
        $today = new SmartDate();
        if ($this->calendarTypeID == 1) {
            $year = $today->getJalaliYear();
            $becomingDate = new SmartDate($year . '-' . $this->month . '-' . $this->day, 'string', 'jalali');
            if ($today->getTimestamp() - $becomingDate->getTimestamp() < 0)
                return $becomingDate;
            else
                return new SmartDate($year + 1 . '-' . $this->month . '-' . $this->day, 'string', 'jalali');
        }
        elseif ($this->calendarTypeID == 2) {
            $year = $today->getHijriYear();
            $becomingDate = new SmartDate($year . '-' . $this->month . '-' . $this->day, 'string', 'hijri');
            if ($today->getTimestamp() - $becomingDate->getTimestamp() < 0)
                return $becomingDate;
            else
                return new SmartDate($year + 1 . '-' . $this->month . '-' . $this->day, 'string', 'hijri');
        }
        elseif ($this->calendarTypeID == 3) {
            $year = $today->getGregorianYear();
            $becomingDate = new SmartDate($year . '-' . $this->month . '-' . $this->day, 'string', 'gregorian');
            if ($today->getTimestamp() - $becomingDate->getTimestamp() < 0)
                return $becomingDate;
            else
                return new SmartDate($year + 1 . '-' . $this->month . '-' . $this->day, 'string', 'gregorian');
        }
        return false;
    }

    /**
     * @return Type
     */
    public function getCalendarType(): Type
    {
        global $calendarTypes;
        return $calendarTypes[$this->calendarTypeID];
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        global $occasionTypes;
        return $occasionTypes[$this->typeID];
    }

    /**
     * @return bool
     */
    public function isHoliday(): bool
    {
        return $this->isHoliday;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @return string
     */
    public function getDisplaySubject(): string
    {
        $detail = $this->getDetail();
        return $this->getSubject() . (($detail != '') ? "(" . $detail . ")" : '');
    }

    // endregion

    // region Class Static Functions::


    /**
     * @param int $ID
     * @param int $day
     * @param int $month
     * @param int $calendarTypeID
     * @param int $typeID
     * @param bool $isHoliday
     * @param string $subject
     * @param string $detail
     * @return Occasion
     */
    public static function Create(int $ID, int $day, int $month, int $calendarTypeID, int $typeID, bool $isHoliday, string $subject, string $detail = ''): Occasion
    {
        return new self($ID, $day, $month, $calendarTypeID, $typeID, $isHoliday, $subject, $detail);
    }

    /**
     * @param int $ID
     * @return Occasion | false
     */
    public static function Get(int $ID)
    {
        $smartDB = new SmartDB();
        $queryResult = $smartDB->select()->from('wp_occasions')->where('ID')->equalTo($ID)->execute();

        $result = [];
        foreach ($queryResult as $dbDay) {
            $result[] = self::Create($dbDay->ID, $dbDay->day, $dbDay->month, $dbDay->calendar_type_id, $dbDay->type_id, $dbDay->is_holiday, $dbDay->subject, ($dbDay->detail == null) ? '' : $dbDay->detail);
        }
        return count($result) == 0 ? false : $result[0];
    }

    /**
     * @param int $day
     * @param int $month
     * @param int $calendarTypeID
     * @param int|null $typeID
     * @param float|null $isHoliday
     * @return Occasion[]
     */
    public static function GetDayOccasions(int $day, int $month, int $calendarTypeID = 1, int $typeID = null, float $isHoliday = null): array
    {
        $smartDB = new SmartDB();
        $query = $smartDB->select()->from('wp_occasions')->where('day')->equalTo($day)->and('month')->equalTo($month)->and('calendar_type_id')->equalTo($calendarTypeID);
        if ($typeID != null)
            $query->and('type_id')->equalTo($typeID);
        if ($isHoliday != null)
            $query->and('is_holiday')->equalTo($isHoliday);
        $queryResult = $query->execute();

        $result = [];
        foreach ($queryResult as $dbDay) {
            $result[] = self::Create($dbDay->ID, $dbDay->day, $dbDay->month, $dbDay->calendar_type_id, $dbDay->type_id, $dbDay->is_holiday, $dbDay->subject, ($dbDay->detail == null) ? '' : $dbDay->detail);
        }
        return $result;
    }

    /**
     * @param int $year
     * @param int $month
     * @return Occasion[]
     */
    public static function GetMonthAllOccasions(int $year, int $month): array
    {
        $result = [];
        $periodStartDate = new SmartDate($year . '-' . $month . '-1', 'string', 'jalali');
        $periodEndDate = $periodStartDate->getJalaliCurrentMonthLastDay();
        for ($i = 0; $i < $periodEndDate->getJalaliDay(); $i++) {
            $date = $periodStartDate->find($i);
            $result = array_merge($result, $date->getAllOccasions());
        }
        return $result;
    }


    /**
     * @return Occasion[]
     */
    public static function GetAllOccasions(): array
    {
        $smartDB = new SmartDB();
        $queryResult = $smartDB->select()->from('wp_occasions')->execute();

        $result = [];
        foreach ($queryResult as $dbDay) {
            $result[] = self::Create($dbDay->ID, $dbDay->day, $dbDay->month, $dbDay->calendar_type_id, $dbDay->type_id, $dbDay->is_holiday, $dbDay->subject, ($dbDay->detail == null) ? '' : $dbDay->detail);
        }
        return $result;
    }

    //endregion

}
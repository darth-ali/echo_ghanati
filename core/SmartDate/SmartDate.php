<?php


namespace SmartDate;

class SmartDate
{
    private int $timestamp;

    private string $format;
    private string $timeZone = 'Asia/Tehran';


    /**
     * SmartDate constructor.
     * @param string $input :: مقدار ورودی که میتواند هم تاریخ شمسی و هم میلادی به صورت رشته باشد و هم تایم استمپ
     * @param $inputType :: 'timestamp','string : 1399-02-12'
     * @param $dateType :: 'gregorian','jalali','hijri'
     */
    public function __construct(string $input = '', string $inputType = 'timestamp', string $dateType = 'gregorian')
    {
        if ($input == '') {
            $this->timestamp = time();
        }
        else {
            if ($inputType == 'string') {
                if ($dateType == 'jalali') {
                    $inputArray = explode('-', $input);
                    $input = SmartDate::JalaliToGregorian($inputArray[0], $inputArray[1], $inputArray[2], '-');
                }
                elseif ($dateType == 'hijri') {
                    $input = self::HijriToGregorian($input);
                }
                $this->timestamp = strtotime($input);

            }
            elseif ($inputType = 'timestamp') {
                $this->timestamp = $input;
            }
            else {
                $this->timestamp = time();
            }
        }
    }


    // region Class Base Public Functions::

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    /**
     * @return string|false
     */
    public function getTime()
    {
        return date('H:i:s', $this->getTimestamp());
    }
    // endregion

    // region Class Gregorian Base Functions::

    /**
     * @return int روز چندم ماه است. مثلا ۲۶
     */
    public function getGregorianDay(): int
    {
        $gregorianDateTime = getdate($this->timestamp);

        return intval($gregorianDateTime['mday']);
    }

    /**
     * @return string چند شنبه است. مثلا پنجشنبه
     */
    public function getGregorianWeekDay(): string
    {
        $gregorianDateTime = getdate($this->timestamp);

        return $gregorianDateTime['weekday'];
    }

    /**
     * @return int روز چندم هفته است. مثلا پنجشنبه روز ۶ام هفته است
     */
    public function getGregorianWeekDayNumber(): int
    {
        $gregorianDateTime = getdate($this->timestamp);

        return intval($gregorianDateTime['wday']);
    }

    /**
     * @return int ماه چندم سال است. مثلا مهر ماه ۷ است
     */
    public function getGregorianMonth(): int
    {
        $gregorianDateTime = getdate($this->timestamp);

        return intval($gregorianDateTime['mon']);
    }


    /**
     * @return string اسم ماه را نشان می‌دهد. مثلا مهر
     */
    public function getGregorianMonthName(): string
    {
        $gregorianDateTime = getdate($this->timestamp);
        return $gregorianDateTime['month'];
    }

    /**
     * @return int سال را نشان می‌دهد
     */
    public function getGregorianYear(): int
    {
        $gregorianDateTime = getdate($this->timestamp);
        return intval($gregorianDateTime['year']);
    }

    /**
     * @return int روز چندم سال است را نشان می‌دهد.
     */
    public function getGregorianYearDays(): int
    {
        $gregorianDateTime = getdate($this->timestamp);
        return intval($gregorianDateTime['yday']);
    }
    // endregion

    // region Class Gregorian Pro Functions::

    /**
     * @param string $separator
     * @return string خروجی به صورت رشته بر‌میگردد. مثل: 12-21-2020
     */
    public function getGregorianDateString(string $separator = '-'): string
    {
        return $this->getGregorianYear() . $separator . $this->getGregorianMonth() . $separator . $this->getGregorianDay();
    }

    /**
     * @return int[] خروجی به این صورت خواهد بود: [2020,12,12]
     */
    public function getGregorianDateArray(): array
    {
        return [
            $this->getGregorianYear(),
            $this->getGregorianMonth(),
            $this->getGregorianDay(),
        ];
    }

    /**
     * @return SmartDate تاریخ اولین روز هفته را برمی‌گرداند
     */
    public function getGregorianCurrentWeekFirstDay(): SmartDate
    {
        $timeStamp = strtotime($this->getGregorianDateString() . ' -' . $this->getGregorianWeekDayNumber() . ' days');
        return new SmartDate($timeStamp);
    }

    /**
     * @param int $gotoWeek یک عدد است که به صورت مثبت و منفی می‌باشد و نشان دهنده چند هفته قبل یا بعد می‌باشد. عدد صفر به معنی هفته جاری است که می‌تواند وارد نشود
     * @return SmartDate[] لیستی از روز‌ها را بر‌می‌گرداند
     */
    public static function GregorianWeek(int $gotoWeek = 0): array
    {
        $selectedDate = new SmartDate();
        $selectedDate->move($gotoWeek * 7);
        $selectedDateWeekFirstDay = $selectedDate->getGregorianCurrentWeekFirstDay();

        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $selectedDateWeekFirstDay->find($i);
        }
        return $week;
    }
    // endregion

    // region Class Jalali Base Functions::
    /**
     * @return int روز چندم ماه است. مثلا ۲۶
     */
    public function getJalaliDay(): int
    {
        $jalaliDateTime = SmartDate::JalaliGetDate($this->timestamp);

        return intval($jalaliDateTime['mday']);
    }

    /**
     * @return string چند شنبه است. مثلا پنجشنبه
     */
    public function getJalaliWeekDay(): string
    {
        $jalaliDateTime = SmartDate::JalaliGetDate($this->timestamp);

        return $jalaliDateTime['weekday'];
    }

    /**
     * @return int روز چندم هفته است. مثلا پنجشنبه روز ۶ام هفته است
     */
    public function getJalaliWeekDayNumber(): int
    {
        $jalaliDateTime = SmartDate::JalaliGetDate($this->timestamp);

        return $jalaliDateTime['wday'];
    }

    /**
     * @return int ماه چندم سال است. مثلا مهر ماه ۷ است
     */
    public function getJalaliMonth(): int
    {
        $jalaliDateTime = SmartDate::JalaliGetDate($this->timestamp);
        return intval($jalaliDateTime['mon']);
    }

    /**
     * @return string اسم ماه را نشان می‌دهد. مثلا مهر
     */
    public function getJalaliMonthName(): string
    {
        $jalaliDateTime = SmartDate::JalaliGetDate($this->timestamp);

        return $jalaliDateTime['month'];
    }

    /**
     * @return int سال را نشان می‌دهد
     */
    public function getJalaliYear(): int
    {
        $jalaliDateTime = SmartDate::JalaliGetDate($this->timestamp);
        return intval($jalaliDateTime['year']);
    }

    /**
     * @return int روز چندم سال است را نشان می‌دهد.
     */
    public function getJalaliYearDays(): int
    {
        $jalaliDateTime = SmartDate::JalaliGetDate($this->timestamp);
        return intval($jalaliDateTime['yday']);
    }
    // endregion

    // region Class Jalali Pro Functions::

    /**
     * @param string $separator
     * @return string خروجی به صورت رشته بر‌میگردد. مثل: ۳-۲-۱۴۰۰
     */

    public function getJalaliDateString(string $separator = '-'): string
    {
        return $this->getJalaliYear() . $separator . $this->getJalaliMonth() . $separator . $this->getJalaliDay();
    }

    /**
     * @param bool $showTime این مشخص می‌کنید که زمان هم نشان داده شود یا نه
     * @return string خروجی به این صورت خواهد بود: شنبه ۲۴ آذر ۱۳۹۲(ساعت ۷:۲۳:۱۰)
     */
    public function getJalaliDisplayDate(bool $showTime = false, bool $showOccasion = false, bool $showHoliday = false): string
    {

        $display = $this->getJalaliWeekDay() . ' ' . $this->getJalaliDay() . ' ' . $this->getJalaliMonthName() . ' ' . $this->getJalaliYear();

        $display .= ($showOccasion && $this->getDisplayOccasions() != '') ? ' - ' . $this->getDisplayOccasions() : '';
        $display .= ($showHoliday && $this->isHoliday()) ? ' - تعطیل' : '';

        if (!$showTime)
            return $display;
        else
            return '<strong>' . $display . '</strong>' . ' (ساعت ' . $this->getTime() . ')';

    }


    /**
     * @return int[] خروجی به این صورت خواهد بود: [1398,12,12]
     */

    public function getJalaliDateArray(): array
    {
        return [
            $this->getJalaliYear(),
            $this->getJalaliMonth(),
            $this->getJalaliDay(),
        ];
    }

    /**
     * @return SmartDate اولین روز سال شمسی را برمی‌گرداند
     */
    public function getJalaliCurrentYearFirstDay(): SmartDate
    {
        return new SmartDate($this->getJalaliYear() . '-1-1', 'string', 'jalali');
    }

    /**
     * @return SmartDate آخرین روز سال شمسی را بر‌میگرداند
     */
    public function getJalaliCurrentYearLastDay(): SmartDate
    {
        $thisLastMonth = new SmartDate($this->getJalaliYear() . '-12-1', 'string', 'jalali');
        return $thisLastMonth->getJalaliCurrentMonthLastDay();
    }

    /**
     * @return SmartDate آخرین روز شمسی این ماه
     */
    public function getJalaliCurrentMonthLastDay(): SmartDate
    {
        $month = $this->getJalaliMonth();
        $year = $this->getJalaliYear();
        if ($month == 12) {
            $mod = (($year + 12) % 33) % 4;
            if ($mod == 1)
                $l_d = 30;
            else
                $l_d = 29;

        }
        else {
            $l_d = (31 - (int)($month / 6.5));
        }
        return new SmartDate($year . '-' . $month . '-' . $l_d, 'string', 'jalali');
    }

    /**
     * @return SmartDate اولین روز شمسی این ماه
     */
    public function getJalaliCurrentMonthFirsDay(): SmartDate
    {
        $month = $this->getJalaliMonth();
        $year = $this->getJalaliYear();
        return new SmartDate($year . '-' . $month . '-1', 'string', 'jalali');
    }

    /**
     * @return SmartDate
     */
    public function getJalaliCurrentWeekFirstDay(): SmartDate
    {
        $timeStamp = strtotime($this->getGregorianDateString() . ' -' . $this->getJalaliWeekDayNumber() . ' days');
        return new SmartDate($timeStamp);
    }

    /**
     * @return SmartDate
     */
    public function getJalaliCurrentWeekLastDay(): SmartDate
    {
        $timeStamp = strtotime($this->getGregorianDateString() . ' +' . (6 - $this->getJalaliWeekDayNumber()) . ' days');
        return new SmartDate($timeStamp);
    }

    /**
     * @return SmartDate :: یک ماه بعد این تاریخ
     */
    public function getJalaliNextMonth(): SmartDate
    {
        if ($this->getJalaliMonth() == 12) {
            $newDate = new SmartDate($this->getJalaliYear() + 1 . '-1-' . $this->getJalaliDay(), 'string', 'jalali');
        }
        else {
            $newDate = new SmartDate($this->getJalaliYear() . '-' . (int)($this->getJalaliMonth() + 1) . '-' . $this->getJalaliDay(), 'string', 'jalali');

        }
        return $newDate;
    }

    /**
     * @return SmartDate ::یک ماه قبل این تاریخ
     */
    public function getJalaliPreviousMonth(): SmartDate
    {
        if ($this->getJalaliMonth() == 1) {
            $newDate = new SmartDate((int)($this->getJalaliYear() - 1) . '-12-' . $this->getJalaliDay(), 'string', 'jalali');
        }
        else {
            $newDate = new SmartDate($this->getJalaliYear() . '-' . (int)($this->getJalaliMonth() - 1) . '-' . $this->getJalaliDay(), 'string', 'jalali');

        }
        return $newDate;
    }



    // endregion

    // region Class Hijri Base Functions::

    /**
     * @return int روز چندم ماه است. مثلا ۲۶
     */
    public function getHijriDay(): int
    {
        return intval(self::GregorianToHijri($this->getGregorianDateString())[2]);
    }

    /**
     * @return int ماه چندم سال است. مثلا مهر ماه ۷ است
     */
    public function getHijriMonth(): int
    {
        return intval(self::GregorianToHijri($this->getGregorianDateString())[1]);
    }

    /**
     * @return int سال را نشان می‌دهد
     */
    public function getHijriYear(): int
    {
        return intval(self::GregorianToHijri($this->getGregorianDateString())[0]);
    }

    // endregion

    // region Class Public Pro Functions::

    /**
     * @param $howManyDays :: روزهایی که آبجکت تاریخ باید حرکت کند که هم منفی می‌تواند باشد و هم مثبت. عدد صفر به معنی همان روز می‌باشد
     * این تابع هیج مقداری برنمیگرداند و صرفا آبجکت موجود را آپدیت می‌کند
     *
     */
    public function move($howManyDays): void
    {
        if ($howManyDays == 0)
            $howManyDays = '';
        else $howManyDays = ' ' . $howManyDays . ' days';
        $this->timestamp = strtotime($this->getGregorianDateString() . $howManyDays);
    }

    /**
     * @param int $howMany :: تعدادی که آبجکت تاریخ باید حرکت کند که هم منفی می‌تواند باشد و هم مثبت. عدد صفر به معنی بدون حرکت می‌باشد
     * @param string $goType :: نوع حرکت را مشخص میکند که عدد قبلی نشان دهنده روز است یا هقته یا ماه یا سال. به عنوان مثال ۲روز به عقب یا ۲هفته به عقب
     *                          'day','week','year'
     * @return SmartDate :: تاریخ چند روز بعد یا چند روز قبل تاریخ آبجکت فعلی رو به صورت یه آبجکت بر‌میگردونه
     */
    public function find(int $howMany, string $goType = 'day'): SmartDate
    {
        if ($howMany == 0)
            $howMany = '';
        else $howMany = ' ' . $howMany . ' ' . $goType;
        $timestamp = strtotime($this->getGregorianDateString() . $howMany);
        return new SmartDate($timestamp);
    }

    /**
     * @param string $prefix :: جمله پیشوند
     * @param string $suffix :: جمله پسوند
     * @return string :: انتشار در ۴ ماه پیش
     */
    public function howLongAgo(string $prefix = '', string $suffix = ''): string
    {
        return $prefix . ' ' . '<strong>' . human_time_diff($this->getTimestamp(), strtotime(current_time('mysql'))) . '</strong>' . ' ' . $suffix;

    }

    /**
     * @return bool
     */
    public function haveOccasion(): bool
    {
        return count($this->getAllOccasions()) > 0;
    }

    /**
     * @return Occasion[]
     */
    public function getAllOccasions(): array
    {
        $result = [];
        foreach ($this->getHijriOccasions() as $occasion) {
            $result[] = $occasion;
        }
        foreach ($this->getGregorianOccasions() as $occasion) {
            $result[] = $occasion;
        }
        foreach ($this->getJalaliOccasions() as $occasion) {
            $result[] = $occasion;
        }
        return $result;

    }

    /**
     * @return Occasion[]
     */
    public function getHijriOccasions(): array
    {
        return Occasion::GetDayOccasions($this->getHijriDay(), $this->getHijriMonth(), 2);

    }

    /**
     * @return Occasion[]
     */
    public function getJalaliOccasions(): array
    {
        return Occasion::GetDayOccasions($this->getJalaliDay(), $this->getJalaliMonth(), 1);

    }

    /**
     * @return Occasion[]
     */
    public function getGregorianOccasions(): array
    {
        return Occasion::GetDayOccasions($this->getGregorianDay(), $this->getGregorianMonth(), 3);

    }
    /**
     * @return bool
     */
    public function isHoliday(): bool
    {
        if ($this->getJalaliWeekDay() == 6)
            return true;
        foreach ($this->getAllOccasions() as $occasion)
            if ($occasion->isHoliday())
                return true;

        return false;
    }

    /**
     * @return string
     */
    public function getDisplayOccasions(): string
    {
        $array = [];
        foreach ($this->getAllOccasions() as $occasion)
            $array[] = $occasion->getDisplaySubject();

        return implode('، ', $array);
    }


    //endregion

    // region Class Public Static Pro Functions::


    /**
     * @return SmartDate[]
     */
    public static function YearRemainingDays(): array
    {
        $currentDay = new SmartDate();
        $pointer = $currentDay;
        $result = [];

        $yearLastDay = $currentDay->getJalaliCurrentYearLastDay();
        for ($i = $currentDay->getJalaliYearDays(); $i < $yearLastDay->getJalaliYearDays() + 1; $i++) {
            $result[] = $pointer;
            $pointer->move(1);
        }
        return $result;
    }


    /**
     * @return SmartDate
     */
    public static function Yesterday(): SmartDate
    {
        $today = new SmartDate();
        return $today->find(-1);
    }

    /**
     * @return SmartDate
     */
    public static function Tomorrow(): SmartDate
    {
        $today = new SmartDate();
        return $today->find(1);
    }

    /**
     * @param SmartDate $dateStart
     * @param SmartDate $dateFinish
     * @param string $output :: 'array','seconds','display'
     * @return int[]|int|string
     */
    public static function Different(SmartDate $dateStart, SmartDate $dateFinish, string $output = 'array')
    {
        $totalSeconds = abs($dateStart->getTimestamp() - $dateFinish->getTimestamp());
        $h = $totalSeconds / 3600;
        $remain = $totalSeconds / 3600;

        $m = $remain / 60;
        $remain = $remain % 60;

        $s = $remain;
        if ($output == 'array') {
            $result = [$h, $m, $s];
        }
        elseif ($output == 'display') {
            $result = ($h != 0) ? $h . ' ساعت و ' : '';
            $result .= ($m != 0) ? $m . ' دقیقه و ' : '';
            $result .= ($s != 0) ? $s . ' ثانیه' : '';
        }
        else {
            $result = $totalSeconds;

        }
        return $result;

    }

    /**
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function JalaliCalendar(int $year, int $month): array
    {
        $firstDayOfMonth = new SmartDate($year . '-' . $month . '-1', 'string', 'jalali');
        $lastDayOfMonth = $firstDayOfMonth->getJalaliCurrentMonthLastDay();

        $firstDayStartLocation = $firstDayOfMonth->getJalaliWeekDayNumber();
        $result = [];
        $rowCounter = 0;
        $colCounter = $firstDayStartLocation;
        for ($i = $firstDayOfMonth->getJalaliDay(); $i < $lastDayOfMonth->getJalaliDay() + 1; $i++) {
            $result[$rowCounter][$colCounter] = $i;
            if (++$colCounter > 6) {
                $colCounter = 0;
                $rowCounter++;
            }


        }
        return $result;
    }


    /**
     * @param int $gotoWeek :: یک عدد است که به صورت مثبت و منفی می‌باشد و نشان دهنده چند هفته قبل یا بعد می‌باشد. عدد صفر به معنی هفته جاری است که می‌تواند وارد نشود
     * @return SmartDate[] :: SmartDate List (one week).
     */
    public static function JalaliWeek(int $gotoWeek = 0): array
    {
        $selectedDate = new SmartDate();
        $selectedDate->move($gotoWeek * 7);
        $selectedDateWeekFirstDay = $selectedDate->getJalaliCurrentWeekFirstDay();

        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $selectedDateWeekFirstDay->find($i);
        }
        return $week;
    }

    /**
     * @param int $gotoMonth
     * @return SmartDate[]
     */
    public static function JalaliMonth(int $gotoMonth = 0): array
    {
        $today = new SmartDate();
        $dateAfterGo = $today->find($gotoMonth, 'month');
        $thisMonth = $dateAfterGo->getJalaliMonth();
        $thisYear = $dateAfterGo->getJalaliYear();
        $thisMonthLastDayNumber = $dateAfterGo->getJalaliCurrentMonthLastDay()->getJalaliDay();

        $monthDays = [];

        for ($day = 1; $day < $thisMonthLastDayNumber + 1; $day++) {
            $monthDays[] = new SmartDate($thisYear . '-' . $thisMonth . '-' . $day, 'string', 'jalali');
        }
        return $monthDays;

    }

    /**
     * @param int $gotoMonth
     * @return SmartDate[][]
     */
    public static function JalaliMonthTable(int $gotoMonth = 0): array
    {
        $table = [];
        foreach (self::JalaliMonth($gotoMonth) as $day) {
            $table[][$day->getJalaliWeekDay()] = $day->getJalaliDay();
        }
        return $table;
    }
    //endregion

    // region Class Private Static Functions::


    /**
     * @param $str
     * @param string $mod
     * @param string $mf
     * @return string|string[]
     */
    private static function NumberConverter($str, $mod = 'en', $mf = '٫')
    {
        $num_a = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
        $key_a = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf];
        return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
    }

    /**
     * @param $array
     * @param string $mod
     * @return mixed|string
     */
    private static function JalaliDateWords($array, $mod = ''): string
    {
        foreach ($array as $type => $num) {
            $num = (int)SmartDate::NumberConverter($num);
            switch ($type) {

                case 'ss':
                    $sl = strlen($num);
                    $xy3 = substr($num, 2 - $sl, 1);
                    $h3 = $h34 = $h4 = '';
                    if ($xy3 == 1) {
                        $p34 = '';
                        $k34 = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
                        $h34 = $k34[substr($num, 2 - $sl, 2) - 10];
                    }
                    else {
                        $xy4 = substr($num, 3 - $sl, 1);
                        $p34 = ($xy3 == 0 or $xy4 == 0) ? '' : ' و ';
                        $k3 = ['', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
                        $h3 = $k3[$xy3];
                        $k4 = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
                        $h4 = $k4[$xy4];
                    }
                    $array[$type] = (($num > 99) ? str_replace(
                                ['12', '13', '14', '19', '20'],
                                ['هزار و دویست', 'هزار و سیصد', 'هزار و چهارصد', 'هزار و نهصد', 'دوهزار'],
                                substr($num, 0, 2)
                            ) . ((substr($num, 2, 2) == '00') ? '' : ' و ') : '') . $h3 . $p34 . $h34 . $h4;
                    break;

                case 'mm':
                    $key = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'rr':
                    $key = [
                        'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه', 'ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده', 'بیست', 'بیست و یک', 'بیست و دو', 'بیست و سه', 'بیست و چهار', 'بیست و پنج', 'بیست و شش', 'بیست و هفت', 'بیست و هشت', 'بیست و نه', 'سی', 'سی و یک',
                    ];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'rh':
                    $key = ['یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'];
                    $array[$type] = $key[$num];
                    break;

                case 'sh':
                    $key = ['مار', 'اسب', 'گوسفند', 'میمون', 'مرغ', 'سگ', 'خوک', 'موش', 'گاو', 'پلنگ', 'خرگوش', 'نهنگ'];
                    $array[$type] = $key[$num % 12];
                    break;

                case 'mb':
                    $key = ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'ff':
                    $key = ['بهار', 'تابستان', 'پاییز', 'زمستان'];
                    $array[$type] = $key[(int)($num / 3.1)];
                    break;

                case 'km':
                    $key = ['فر', 'ار', 'خر', 'تی‍', 'مر', 'شه‍', 'مه‍', 'آب‍', 'آذ', 'دی', 'به‍', 'اس‍'];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'kh':
                    $key = ['ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش'];
                    $array[$type] = $key[$num];
                    break;

                default:
                    $array[$type] = $num;
            }
        }
        return ($mod === '') ? $array : implode($mod, $array);
    }

    /**
     * @param $jy
     * @param $jm
     * @param $jd
     * @param string $mod
     * @return array|string
     */
    private static function JalaliToGregorian($jy, $jm, $jd, $mod = '')
    {
        [$jy, $jm, $jd] = explode('_', SmartDate::NumberConverter($jy . '_' . $jm . '_' . $jd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
        $jy += 1595;
        $days = -355668 + (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
        $gy = 400 * ((int)($days / 146097));
        $days %= 146097;
        if ($days > 36524) {
            $gy += 100 * ((int)(--$days / 36524));
            $days %= 36524;
            if ($days >= 365)
                $days++;
        }
        $gy += 4 * ((int)($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $gy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $gd = $days + 1;
        $sal_a = [0, 31, (($gy % 4 == 0 and $gy % 100 != 0) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        for ($gm = 0; $gm < 13 and $gd > $sal_a[$gm]; $gm++)
            $gd -= $sal_a[$gm];
        return ($mod == '') ? [$gy, $gm, $gd] : $gy . $mod . $gm . $mod . $gd;
    }

    /**
     * @param $gy
     * @param $gm
     * @param $gd
     * @param string $mod
     * @return array|string
     */
    private static function GregorianToJalali($gy, $gm, $gd, $mod = '')
    {
        [$gy, $gm, $gd] = explode('_', SmartDate::NumberConverter($gy . '_' . $gm . '_' . $gd));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) + $gd + $g_d_m[$gm - 1];
        $jy = -1595 + (33 * ((int)($days / 12053)));
        $days %= 12053;
        $jy += 4 * ((int)($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $jy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jm = 1 + (int)($days / 31);
            $jd = 1 + ($days % 31);
        }
        else {
            $jm = 7 + (int)(($days - 186) / 30);
            $jd = 1 + (($days - 186) % 30);
        }
        return ($mod == '') ? [$jy, $jm, $jd] : $jy . $mod . $jm . $mod . $jd;
    }

    /**
     * @param string $timestamp
     * @param string $none
     * @param string $timezone
     * @param string $tn
     * @return array
     */
    private static function JalaliGetDate($timestamp = '', $none = '', $timezone = 'Asia/Tehran', $tn = 'en'): array
    {
        $ts = ($timestamp === '') ? time() : SmartDate::NumberConverter($timestamp);
        $jdate = explode('_', SmartDate::JalaliDate('F_G_i_j_l_n_s_w_Y_z', $ts, '', $timezone, $tn));
        return [
            'seconds' => SmartDate::NumberConverter((int)SmartDate::NumberConverter($jdate[6]), $tn),
            'minutes' => SmartDate::NumberConverter((int)SmartDate::NumberConverter($jdate[2]), $tn),
            'hours' => $jdate[1],
            'mday' => $jdate[3],
            'wday' => $jdate[7],
            'mon' => $jdate[5],
            'year' => $jdate[8],
            'yday' => $jdate[9],
            'weekday' => $jdate[4],
            'month' => $jdate[0],
            0 => SmartDate::NumberConverter($ts, $tn),
        ];
    }

    /**
     * @param $format
     * @param string $timestamp
     * @param string $none
     * @param string $time_zone
     * @param string $tr_num
     * @return string|string[]
     */
    private static function JalaliDate($format, $timestamp = '', $none = '', $time_zone = 'Asia/Tehran', $tr_num = 'fa')
    {
        $T_sec = 0;/* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

        if ($time_zone != 'local')
            date_default_timezone_set(($time_zone === '') ? 'Asia/Tehran' : $time_zone);
        $ts = $T_sec + (($timestamp === '') ? time() : SmartDate::NumberConverter($timestamp));
        $date = explode('_', date('H_i_j_n_O_P_s_w_Y', $ts));
        [$j_y, $j_m, $j_d] = SmartDate::GregorianToJalali($date[8], $date[3], $date[2]);
        $doy = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d - 1 : (($j_m - 7) * 30) + $j_d + 185;
        $kab = (((($j_y + 12) % 33) % 4) == 1) ? 1 : 0;
        $sl = strlen($format);
        $out = '';
        for ($i = 0; $i < $sl; $i++) {
            $sub = substr($format, $i, 1);
            if ($sub == '\\') {
                $out .= substr($format, ++$i, 1);
                continue;
            }
            switch ($sub) {

                case 'E':
                case 'R':
                case 'x':
                case 'X':
                    $out .= 'http://jdf.scr.ir';
                    break;

                case 'B':
                case 'e':
                case 'g':
                case 'G':
                case 'h':
                case 'I':
                case 'T':
                case 'u':
                case 'Z':
                    $out .= date($sub, $ts);
                    break;

                case 'a':
                    $out .= ($date[0] < 12) ? 'ق.ظ' : 'ب.ظ';
                    break;

                case 'A':
                    $out .= ($date[0] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;

                case 'b':
                    $out .= (int)($j_m / 3.1) + 1;
                    break;

                case 'c':
                    $out .= $j_y . '/' . $j_m . '/' . $j_d . ' ،' . $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[5];
                    break;

                case 'C':
                    $out .= (int)(($j_y + 99) / 100);
                    break;

                case 'd':
                    $out .= ($j_d < 10) ? '0' . $j_d : $j_d;
                    break;

                case 'D':
                    $out .= SmartDate::JalaliDateWords(['kh' => $date[7]], ' ');
                    break;

                case 'f':
                    $out .= SmartDate::JalaliDateWords(['ff' => $j_m], ' ');
                    break;

                case 'F':
                    $out .= SmartDate::JalaliDateWords(['mm' => $j_m], ' ');
                    break;

                case 'H':
                    $out .= $date[0];
                    break;

                case 'i':
                    $out .= $date[1];
                    break;

                case 'j':
                    $out .= $j_d;
                    break;

                case 'J':
                    $out .= SmartDate::JalaliDateWords(['rr' => $j_d], ' ');
                    break;

                case 'k';
                    $out .= SmartDate::NumberConverter(100 - (int)($doy / ($kab + 365.24) * 1000) / 10, $tr_num);
                    break;

                case 'K':
                    $out .= SmartDate::NumberConverter((int)($doy / ($kab + 365.24) * 1000) / 10, $tr_num);
                    break;

                case 'l':
                    $out .= SmartDate::JalaliDateWords(['rh' => $date[7]], ' ');
                    break;

                case 'L':
                    $out .= $kab;
                    break;

                case 'm':
                    $out .= ($j_m > 9) ? $j_m : '0' . $j_m;
                    break;

                case 'M':
                    $out .= SmartDate::JalaliDateWords(['km' => $j_m], ' ');
                    break;

                case 'n':
                    $out .= $j_m;
                    break;

                case 'N':
                    $out .= $date[7] + 1;
                    break;

                case 'o':
                    $jdw = ($date[7] == 6) ? 0 : $date[7] + 1;
                    $dny = 364 + $kab - $doy;
                    $out .= ($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y);
                    break;

                case 'O':
                    $out .= $date[4];
                    break;

                case 'p':
                    $out .= SmartDate::JalaliDateWords(['mb' => $j_m], ' ');
                    break;

                case 'P':
                    $out .= $date[5];
                    break;

                case 'q':
                    $out .= SmartDate::JalaliDateWords(['sh' => $j_y], ' ');
                    break;

                case 'Q':
                    $out .= $kab + 364 - $doy;
                    break;

                case 'r':
                    $key = SmartDate::JalaliDateWords(['rh' => $date[7], 'mm' => $j_m]);
                    $out .= $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[4] . ' ' . $key['rh'] . '، ' . $j_d . ' ' . $key['mm'] . ' ' . $j_y;
                    break;

                case 's':
                    $out .= $date[6];
                    break;

                case 'S':
                    $out .= 'ام';
                    break;

                case 't':
                    $out .= ($j_m != 12) ? (31 - (int)($j_m / 6.5)) : ($kab + 29);
                    break;

                case 'U':
                    $out .= $ts;
                    break;

                case 'v':
                    $out .= SmartDate::JalaliDateWords(['ss' => ($j_y % 100)], ' ');
                    break;

                case 'V':
                    $out .= SmartDate::JalaliDateWords(['ss' => $j_y], ' ');
                    break;

                case 'w':
                    $out .= ($date[7] == 6) ? 0 : $date[7] + 1;
                    break;

                case 'W':
                    $avs = (($date[7] == 6) ? 0 : $date[7] + 1) - ($doy % 7);
                    if ($avs < 0)
                        $avs += 7;
                    $num = (int)(($doy + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    }
                    elseif ($num < 1) {
                        $num = ($avs == 4 or $avs == ((((($j_y % 33) % 4) - 2) == ((int)(($j_y % 33) * 0.05))) ? 5 : 4)) ? 53 : 52;
                    }
                    $aks = $avs + $kab;
                    if ($aks == 7)
                        $aks = 0;
                    $out .= (($kab + 363 - $doy) < $aks and $aks < 3) ? '01' : (($num < 10) ? '0' . $num : $num);
                    break;

                case 'y':
                    $out .= substr($j_y, 2, 2);
                    break;

                case 'Y':
                    $out .= $j_y;
                    break;

                case 'z':
                    $out .= $doy;
                    break;

                default:
                    $out .= $sub;
            }
        }
        return ($tr_num != 'en') ? SmartDate::NumberConverter($out, 'fa', '.') : $out;
    }


    /**
     * @param string $dateString :: YYYY-MM-DD
     * @return string[] :: [1436,2,15]
     */
    private static function GregorianToHijri(string $dateString): array
    {
        $inputArray = explode('-', $dateString);

        $ch = curl_init();
        CURL_SETOPT($ch, CURLOPT_URL, 'http://api.aladhan.com/v1/gToH?date=' . $inputArray[2] . '-' . $inputArray[1] . '-' . $inputArray[0]);
        CURL_SETOPT($ch, CURLOPT_RETURNTRANSFER, True);
        $response = CURL_EXEC($ch);
        $responseObject = json_decode($response);
        $result = [
            $responseObject->data->hijri->year,
            $responseObject->data->hijri->month->number,
            $responseObject->data->hijri->day,
        ];
        return $result;
    }

    /**
     * @param string $dateString :: YYYY-MM-DD
     * @return string :: 2002-2-2
     */
    private static function HijriToGregorian(string $dateString): string
    {
        $inputArray = explode('-', $dateString);

        $ch = curl_init();
        CURL_SETOPT($ch, CURLOPT_URL, 'http://api.aladhan.com/v1/hToG?date=' . $inputArray[2] . '-' . $inputArray[1] . '-' . $inputArray[0]);
        CURL_SETOPT($ch, CURLOPT_RETURNTRANSFER, True);
        $response = CURL_EXEC($ch);
        $responseObject = json_decode($response);
        $result = [
            $responseObject->data->gregorian->year,
            $responseObject->data->gregorian->month->number,
            $responseObject->data->gregorian->day,
        ];
        return implode('-', $result);
    }


    // endregion

}
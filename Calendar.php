<?php

/**
 * Class for counting week day by provided date
 */
class Calendar {

    public $errorMessage;
    private $day;
    private $month;
    private $year;
    private $leapYearDays;
    private $commonYearDays;
    private $dayCountPositiveDirection;

    const PROVIDED_YEAR = 1990;
    const EVEN_MONTH_DAYS = 21;
    const UNEVEN_MONTH_DAYS = 22;
    const YEAR_MONTHS = 13;
    const LEAP_YEAR_MULTIPLICITY = 5;
    const DATE_VALIDATION_PATTERN = '/^(0[1-9]|1[0-9]|2[0-2])[\-,\.,\/](0[1-9]|1[0-3])[\-,\.,\/]([0-9]{4})$/';
    const WEEK_DAYS = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday'
    ];

    public function __construct($dateText)
    {
        if ($this->validate($dateText)) {
            $this->parseDateString($dateText);
        }
    }

    private function validate($dateText)
    {
        if (isset($dateText) && !empty($dateText)) {
            if (preg_match(self::DATE_VALIDATION_PATTERN, $dateText)) {
                return true;
            } else {
                $this->errorMessage = 'Date is not valid!';
                return false;
            }
        }
        $this->errorMessage ='Date can not be blank!';
        return false;
    }

    public function getWeekDayName()
    {
        if (empty($this->errorMessage)) {
            if ($this->year >= self::PROVIDED_YEAR) {
                $this->dayCountPositiveDirection = true;
            } else {
                $this->dayCountPositiveDirection = false;
            }
            $this->getLeapYearDaysCount();
            $this->getCommonYearDaysCount();
            return $this->countDay();
        }
    }

    private function parseDateString($dateText)
    {
        preg_match(self::DATE_VALIDATION_PATTERN, $dateText, $matches);
        $this->day = $matches[1];
        $this->month = $matches[2];
        $this->year = $matches[3];
    }

    private function getLeapYearDaysCount()
    {
        $this->leapYearDays = (intdiv(self::YEAR_MONTHS,2) + 1) * self::EVEN_MONTH_DAYS
                        + intdiv(self::YEAR_MONTHS,2) * self::UNEVEN_MONTH_DAYS;
    }

    private function getCommonYearDaysCount()
    {
        $this->commonYearDays = intdiv(self::YEAR_MONTHS,2) * self::EVEN_MONTH_DAYS
                          + (intdiv(self::YEAR_MONTHS,2) + 1) * self::UNEVEN_MONTH_DAYS;
    }

    private function getDays()
    {
        if ($this->dayCountPositiveDirection) {
            return $this->day;
        } else {
            $dayInAMonth = (
                $this->month % 2 == 0 ||
                ($this->month == self::YEAR_MONTHS && $this->year % self::LEAP_YEAR_MULTIPLICITY == 0)
            ) ?
                self::EVEN_MONTH_DAYS :
                self::UNEVEN_MONTH_DAYS;
            return $dayInAMonth - $this->day + 1;
        }
    }

    private function getMonthsDays()
    {
        if ($this->dayCountPositiveDirection) {
            return ($this->month - 1) % 2 == 0 ?
                self::EVEN_MONTH_DAYS * ($this->month - 1) / 2 + self::UNEVEN_MONTH_DAYS * ($this->month - 1) / 2 :
                self::EVEN_MONTH_DAYS * intdiv($this->month - 1,2) +
                self::UNEVEN_MONTH_DAYS * (intdiv($this->month - 1,2) +1);
        } else {
            if ((self::YEAR_MONTHS - $this->month) % 2 == 0) {
                if ($this->year % self::LEAP_YEAR_MULTIPLICITY == 0 && self::YEAR_MONTHS - $this->month != 0) {
                    return self::EVEN_MONTH_DAYS * ((self::YEAR_MONTHS - $this->month) / 2 + 1)
                                 + self::UNEVEN_MONTH_DAYS * ((self::YEAR_MONTHS - $this->month) / 2 - 1);
                } else {
                    return self::EVEN_MONTH_DAYS * (self::YEAR_MONTHS - $this->month) / 2
                                 + self::UNEVEN_MONTH_DAYS * (self::YEAR_MONTHS - $this->month) / 2 ;
                }
            } else {
                if ($this->year % self::LEAP_YEAR_MULTIPLICITY == 0) {
                    return self::EVEN_MONTH_DAYS * (intdiv(self::YEAR_MONTHS - $this->month, 2) + 1)
                           + self::UNEVEN_MONTH_DAYS * intdiv(self::YEAR_MONTHS - $this->month, 2);
                } else {
                    return self::EVEN_MONTH_DAYS * intdiv(self::YEAR_MONTHS - $this->month, 2)
                           + self::UNEVEN_MONTH_DAYS * (intdiv(self::YEAR_MONTHS - $this->month, 2) + 1);
                }
            }
        }
    }

    private function getYearsDays()
    {
        if ($this->dayCountPositiveDirection) {
            $years = $this->year - self::PROVIDED_YEAR;
            $lYears = ($years % self::LEAP_YEAR_MULTIPLICITY == 0) ?
                intdiv($years,self::LEAP_YEAR_MULTIPLICITY) :
                intdiv($years,self::LEAP_YEAR_MULTIPLICITY) + 1;
            $cYears = $years - $lYears;

            return $lYears * $this->leapYearDays + $cYears * $this->commonYearDays;
        } else {
            $years = self::PROVIDED_YEAR - $this->year;
            $lYears = ($years % self::LEAP_YEAR_MULTIPLICITY == 0) ?
                intdiv($years,self::LEAP_YEAR_MULTIPLICITY) - 1 :
                intdiv($years,self::LEAP_YEAR_MULTIPLICITY);
            $cYears = $years - $lYears - 1;
            return $lYears * $this->leapYearDays + $cYears * $this->commonYearDays;
        }
    }

    private function countDay()
    {
        if ($this->dayCountPositiveDirection) {
            $weekDayIndex = ($this->getYearsDays() + $this->getMonthsDays() + $this->getDays()) % 7;
        } else {
            $weekDayIndex = (7 - ($this->getYearsDays() + $this->getMonthsDays() + $this->getDays()) % 7 + 1) % 7;
        }

        return self::WEEK_DAYS[$weekDayIndex];
    }
}

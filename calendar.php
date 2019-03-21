<?php

$weekDayResult = '';
$error = '';

const PROVIDED_YEAR = 1990;
const EVEN_MONTH_DAYS = 21;
const UNEVEN_MONTH_DAYS = 22;
const YEAR_MONTHS = 13;
const LEAP_YEAR_MULTIPLICITY = 5;

const WEEK_DAYS = [
    0 => 'Sunday',
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday'
];

if (isset($_POST['date']) && !empty($_POST['date'])) {
    $boolResult = preg_match(
        "/^(0[1-9]|1[0-9]|2[0-2])[\-,\.,\/](0[1-9]|1[0-3])[\-,\.,\/]([0-9]{4})$/",
        $_POST['date'],
        $matches
    );
    if (!$boolResult) {
        $error = 'Date is not valid!';
    } else {
        $functionResult = getDayByDate($matches[1], $matches[2], $matches[3]);
        $error = $functionResult['error'];
        $weekDayResult = $functionResult['result'];
    }
} else {
    $error ='Date can not be blank!';
}

function getDayByDate($d, $m, $y) {

    $leapYearDays = (intdiv(YEAR_MONTHS,2) + 1) * EVEN_MONTH_DAYS
                    + intdiv(YEAR_MONTHS,2) * UNEVEN_MONTH_DAYS;
    $commonYearDays = intdiv(YEAR_MONTHS,2) * EVEN_MONTH_DAYS
                      + (intdiv(YEAR_MONTHS,2) + 1) * UNEVEN_MONTH_DAYS;

    if ($y < PROVIDED_YEAR) {
        if ((YEAR_MONTHS - $m) % 2 == 0) {
            if ($y % LEAP_YEAR_MULTIPLICITY == 0 && YEAR_MONTHS - $m != 0) {
                $monthDays = EVEN_MONTH_DAYS * ((YEAR_MONTHS - $m) / 2 + 1)
                             + UNEVEN_MONTH_DAYS * ((YEAR_MONTHS - $m) / 2 - 1);
            } else {
                $monthDays = EVEN_MONTH_DAYS * (YEAR_MONTHS - $m) / 2
                             + UNEVEN_MONTH_DAYS * (YEAR_MONTHS - $m) / 2 ;
            }
        } else {
            if ($y % LEAP_YEAR_MULTIPLICITY == 0) {
                $monthDays = EVEN_MONTH_DAYS * (intdiv( YEAR_MONTHS - $m, 2 ) + 1)
                             + UNEVEN_MONTH_DAYS * intdiv( YEAR_MONTHS - $m, 2 );
            } else {
                $monthDays = EVEN_MONTH_DAYS * intdiv( YEAR_MONTHS - $m, 2 )
                             + UNEVEN_MONTH_DAYS * ( intdiv( YEAR_MONTHS - $m, 2 ) + 1 );
            }
        }

        $years = PROVIDED_YEAR - $y;
        $lYears = ($years % LEAP_YEAR_MULTIPLICITY == 0) ? intdiv($years,LEAP_YEAR_MULTIPLICITY) - 1 :
            intdiv($years,LEAP_YEAR_MULTIPLICITY);
        $cYears = $years - $lYears - 1;
        $yearDays = $lYears * $leapYearDays + $cYears * $commonYearDays;

        $dayInAMonth = ($m % 2 == 0 || ($m == YEAR_MONTHS && $y % LEAP_YEAR_MULTIPLICITY == 0)) ? EVEN_MONTH_DAYS :
            UNEVEN_MONTH_DAYS;
        $days = $dayInAMonth - $d + 1;

        $w = (7 - ($yearDays + $monthDays + $days) % 7 + 1) % 7;
    } else {
        $monthDays = ($m - 1) % 2 == 0 ? EVEN_MONTH_DAYS * ($m - 1) / 2 + UNEVEN_MONTH_DAYS * ($m - 1) / 2 :
            EVEN_MONTH_DAYS * intdiv($m - 1,2) +
            UNEVEN_MONTH_DAYS * (intdiv($m - 1,2) +1);

        $years = $y - PROVIDED_YEAR;
        $lYears = ($years % LEAP_YEAR_MULTIPLICITY == 0) ? intdiv($years,LEAP_YEAR_MULTIPLICITY) :
            intdiv($years,LEAP_YEAR_MULTIPLICITY) + 1;
        $cYears = $years - $lYears;
        $yearDays = $lYears * $leapYearDays + $cYears * $commonYearDays;

        $w = ($yearDays + $monthDays + $d) % 7;
    }

    return [
        'error' => '',
        'result' => WEEK_DAYS[$w]
    ];
}

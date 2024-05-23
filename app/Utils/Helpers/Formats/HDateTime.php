<?php /** @noinspection PhpUnused */


namespace App\Utils\Helpers\Formats;


use App\Utils\Helpers\DB\HDB;
use DateTime;

/**
 * Date & Time helper.
 *
 * @package App\BPM\Utils\Helpers
 */
class HDateTime {

    /**
     * Format constants
     */
    public const FORMAT_DAY = 'd';
    public const FORMAT_MONTH = 'm';
    public const FORMAT_YEAR = 'Y';
    public const FORMAT_DAY_MONTH = 'd.m';
    public const FORMAT_UI = 'd.m.Y';
    public const FORMAT_UI_WITH_TIME = 'd.m.Y H:i:s';
    public const FORMAT_UI_WITH_TIME_MIL = 'd.m.Y H:i:s.u';
    public const FORMAT_SQL = 'Y-m-d';
    public const FORMAT_SQL_WITH_TIME = 'Y-m-d H:i:s';
    public const FORMAT_SQL_WITH_TIME_MIL = 'Y-m-d H:i:s.u';
    public const FORMAT_FILE_NAME_SUFFIX = "_Y_m_d__H:i:s";

    public const C_MAX_DATE_STRING = '31.12.9999';

    /**
     * Дата 31.12.9999
     * @return DateTime
     */
    public static function maxDate() {
        return DateTime::createFromFormat(self::FORMAT_UI, self::C_MAX_DATE_STRING);
    }

    /**
     * Set time 00:00.
     * @param mixed $dateTime
     */
    public static function timeClear($dateTime) {
        $dateTime->setTime(0, 0);
    }

    /**
     * Set time 23:59:59.
     * @param mixed $dateTime
     */
    public static function timeEndOfDay($dateTime) {
        $dateTime->setTime(23, 59, 59);
    }

    /**
     * Add to the end of string current date string "_Y_m_d__H:i:s"
     *
     * @param string          $fileName
     * @param DateTime | null $dateTime
     *
     * @return string
     */
    public static function addFileNameDateTimeSuffix($fileName, $dateTime = null) {
        if ($dateTime == null) {
            $dateTime = new DateTime();
        }
        return $fileName . $dateTime->format(self::FORMAT_FILE_NAME_SUFFIX);
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public static function tomorrow($dateTime) {
        $tomorrow = clone $dateTime;
        $tomorrow->modify('+1 day');
        return $tomorrow;
    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public static function yesterday($dateTime) {
        $yesterday = clone $dateTime;
        $yesterday->modify('-1 day');
        return $yesterday;
    }

    /**
     * Формирует даты из строки (без учета времени)
     * Сначая пробуем формат d.m.Y
     * Потом пробуем формат Y-m-d
     * @param string|DateTime $dateString
     * @param null|DateTime   $defaultValue
     * @return DateTime|null
     */
    public static function strToDate($dateString, $defaultValue = null) {

        if ($dateString instanceof DateTime) {
            return $dateString;
        }

        if (!is_string($dateString)) {
            return $defaultValue;
        }

        $dateTime = DateTime::createFromFormat('d.m.Y H:i:s', $dateString . " 00:00:00");
        $dateTime = $dateTime ? $dateTime : DateTime::createFromFormat(self::FORMAT_SQL_WITH_TIME, $dateString . " 00:00:00");

        $dateTime = $dateTime ? $dateTime : DateTime::createFromFormat(self::FORMAT_UI_WITH_TIME, $dateString);
        $dateTime = $dateTime ? $dateTime : DateTime::createFromFormat(self::FORMAT_SQL_WITH_TIME, $dateString);
        $dateTime = $dateTime ? $dateTime : DateTime::createFromFormat(self::FORMAT_UI_WITH_TIME_MIL, $dateString);
        $dateTime = $dateTime ? $dateTime : DateTime::createFromFormat(self::FORMAT_SQL_WITH_TIME_MIL, $dateString);

        return $dateTime ? $dateTime : $defaultValue;

    }

    /**
     * Сокращает период до указанного количества дней, начало остается неизменным.
     * @param DateTime $dateStart
     * @param DateTime $dateFinish
     * @param int      $daysDiffMax
     */
    public static function maxDiffToStart(DateTime $dateStart, DateTime $dateFinish, $daysDiffMax) {
        $diff = date_diff($dateFinish, $dateStart);
        if ($diff->d > $daysDiffMax) {
            $dateFinish->modify("-" . ($diff->d - $daysDiffMax) . " days"); // приблизить к началу на количество превышающих дней
        }
    }

    /**
     * Поменять даты местами, последовательно.
     * @param DateTime $dateStart
     * @param DateTime $dateFinish
     * @return DateTime - min(date_start,date_finish) дата начала периода
     */
    public static function startFinishNormalize(DateTime &$dateStart, DateTime &$dateFinish) {
        if ($dateStart < $dateFinish) {
            return $dateStart;
        }
        $exchange = $dateStart;
        $dateFinish = $dateStart;
        return $dateStart = $exchange;
    }

    /**
     * @param DateTime $date
     * @return DateTime
     */
    public static function firstDayOfWeek(DateTime $date) {

        $date = clone $date;
        $weekDay = intval($date->format('w')) - 1; // день недели обчно дает 0-вс 1-пн и т.д. надо сдвинуть.

        if ($weekDay < 0) {
            $weekDay = 6;
        }

        if ($weekDay > 0) {
            $date->modify((-$weekDay) . " day");
        }

        return $date;

    }

    /**
     * @param DateTime $dateTime
     * @return DateTime
     */
    public static function lastDayOfWeek(DateTime $dateTime) {
        $dateTime = clone $dateTime;
        $weekDay = intval($dateTime->format('w')) - 1; // день недели обчно дает 0-вс 1-пн и т.д. надо сдвинуть.
        if ($weekDay < 0) {
            $weekDay = 6;
        }
        if ($weekDay < 6) {
            $dateTime->modify((6 - $weekDay) . " day");
        }
        return $dateTime;
    }

    /**
     * @param DateTime|null $dateTime - today when empty
     * @return DateTime
     */
    public static function firstDayOfMonth($dateTime = null) {

        if ($dateTime == null) {
            $dateTime = new DateTime();
        }

        $year = intval($dateTime->format(self::FORMAT_YEAR));
        $month = intval($dateTime->format(self::FORMAT_MONTH));
        $newDate = new DateTime();
        $newDate->setDate($year, $month, 1);

        return $newDate;
    }

    /**
     * @param DateTime|null $dateTime - по умолчанию ставится текущая дата
     * @return DateTime
     */
    public static function lastDayOfMonth($dateTime = null) {
        $newDate = clone $dateTime;
        $newDate->modify('last day of this month');
        return $newDate;
    }


    /**
     * @param DateTime $dateTime
     * @return mixed
     */
    public static function toSQLDate($dateTime) {
        return $dateTime->format(self::FORMAT_SQL);
    }

    /**
     * @param DateTime|null $dateTime
     * @return string
     */
    public static function toSQLDateTime($dateTime = null) {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        }
        return $dateTime->format(HDB::isPgSql()
            ? self::FORMAT_SQL_WITH_TIME_MIL
            : self::FORMAT_SQL_WITH_TIME);
    }

    /**
     * @param DateTime|string|null $dateTime
     * @return string
     */
    public static function toUIDate($dateTime = null) {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        }
        if ($dateTime instanceof DateTime) {
            return $dateTime->format(self::FORMAT_UI);
        }
        return $dateTime;
    }

    /**
     * @param DateTime|mixed $date
     * @param null           $nullValue
     * @return DateTime|mixed
     */
    public static function toUIDateTime($date = 'Now - default value',$nullValue = null) {
        if ($date === 'Now - default value') {
            $date = new DateTime();
        }
        if ($date instanceof DateTime) {
            return $date->format(self::FORMAT_UI_WITH_TIME);
        }
        if ( $date === null ){
            return $nullValue;
        }
        return $date;
    }

    /**
     * @param DateTime      $dateStart
     * @param DateTime      $dateEnd
     * @param DateTime|null $dateTime
     * @return bool
     */
    public static function isDateInPeriod($dateStart, $dateEnd, $dateTime = null) {
        if ($dateTime == null) {
            $dateTime = new DateTime();
        }
        self::timeClear($dateTime);
        if (
            ($dateStart && $dateTime < $dateStart)
            ||
            ($dateEnd && $dateTime > $dateEnd)
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param DateTime $dateStart
     * @param DateTime $dateEnd
     * @return int
     */
    public static function daysInPeriod(DateTime $dateStart, DateTime $dateEnd) {
        $daysDiff = date_diff($dateEnd, $dateStart);
        return abs($daysDiff->days);
    }

}

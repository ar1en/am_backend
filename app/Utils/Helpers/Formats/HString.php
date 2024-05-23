<?php


namespace App\Utils\Helpers\Formats;


use DateTime;

/**
 * Class HString - string helper
 *
 * @package App\About\Utils\Helpers
 */
class HString {


    /**
     * Форматируем дату из запроса в DateTime.
     * Если не распознана дата  - возращает текущую.
     *
     * @param        $dateString - срока с датой в формте d.m.Y или Y-m-d
     * @param        $default
     * @return DateTime|false
     */
    public static function inputToDate($dateString, $default = 'current time') {
        if ($default === 'current time') {
            $default = new DateTime();
        }
        return HDateTime::strToDate($dateString, $default);
    }

    /**
     * Определяет значение параметра как bool
     *
     * @param string $value
     * @param bool   $default - значение по-умолчанию
     * @return bool
     */
    public static function inputToBool($value, $default = false): bool {

        if (  $value === true || $value === 1 || $value === '1' || strtolower($value) === 'true' || intval($value) > 0) {
            return true;
        }

        if ($value === false || $value === 0 || $value === '0' || strtolower($value) === 'false') {
            return false;
        }

        return $default;

    }


    /**
     * @param      $value
     * @param null $default
     * @return int|null
     */
    public static function inputToInt($value, $default = null) {
        if ($value === null) {
            return $default;
        }
        return intval($value);
    }


    /**
     * Implode string[][] and return text.
     * Make columns alignment.
     * [ ['a','b','c'], ['aa','bb','cc'], ['aaa','bbb','ccc']]
     * convert to string
     * "\r\na   b   c\r\naa  bb  cc\r\naaa bbb ccc"
     *
     * @param $arrays - of arrays of string
     * @return string
     */
    public static function implodeArrays($arrays) {

        // check empty arrays
        if (empty($arrays)) {
            return '';
        }

        $result    = [];
        $maxLength = 0;
        foreach ($arrays[0] as $i => $val) {
            $newLength = 0;
            foreach ($arrays as $row => $array) {
                $result[$row] = isset($result[$row])
                    ? $result[$row] . str_repeat(" ", $maxLength - strlen($result[$row]))
                    : '';
                $result[$row] .= ($i > 0 ? " " : "") . $array[$i];
                $newLength    = max($newLength, strlen($result[$row]));
            }
            $maxLength = $newLength;
        }
        return "\r\n" . implode("\r\n", $result);

    }


    /**
     * Make from Family Name Patronymic string as Family N.P.
     * @param $fio
     * @return string
     */
    public static function getFioShort($fio) {
        $list = explode(' ', $fio);
        $fio  = $list[0];
        if (isset($list[1])) {
            $fio .= " " . mb_substr($list[1], 0, 1) . ".";
        }
        if (isset($list[2])) {
            $fio .= mb_substr($list[2], 0, 1) . ".";
        }
        return $fio;
    }


}

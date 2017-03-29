<?php

/**
 * Class DateAndTime
 */
class DateAndTime {

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @param $timestamp
     */
    private function __construct($timestamp) {
        $this->timestamp = $timestamp;
    }

    /**
     * @return DateAndTime
     */
    public static function now() {
        return new self(time());
    }

    /**
     * @param string $format
     * @return bool|string
     */
    public function toString($format = 'Y-m-d H:i:s') {
        return date($format, $this->timestamp);
    }

    /**
     * @return bool|string
     */
    public function toDMYString() {
        return date('d.m.Y.', $this->timestamp);
    }

    /**
     * @param $ymd
     * @return DateAndTime
     */
    public static function fromYMDString($ymd) {
        $ts = (int)strtotime($ymd);
        return new DateAndTime($ts);
    }

    /**
     * @param $dmy
     * @param string $separator
     * @return DateAndTime
     */
    public static function fromDMYString($dmy, $separator = "-") {
        $d = substr($dmy, 0, 2);
        $m = substr($dmy, 2 + strlen($separator), 2);
        $Y = substr($dmy, 4 + 2 * strlen($separator), 4);
        $ts = strtotime($Y . "-" . $m . "-" . $d);
        return new DateAndTime($ts);
    }

    /**
     * @return bool|string
     */
    public function getYear() {
        return date('Y', $this->timestamp);
    }

    /**
     * @return bool|string
     */
    public function getMonth() {
        return date('m', $this->timestamp);
    }

    /**
     * @return bool|string
     */
    public function getDay() {
        return date('d', $this->timestamp);
    }
}
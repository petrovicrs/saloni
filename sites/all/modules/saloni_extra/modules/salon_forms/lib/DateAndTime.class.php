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
     * @return int
     */
    public function getTimestamp() {
        return (int)$this->timestamp;
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
        return $this->toString('d.m.Y.');
    }

    /**
     * @return bool|string
     */
    public function toYMDString() {
        return $this->toString('Y-m-d');
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

    /**
     * @param $dateString
     * @param string $format
     * @return bool
     */
    public static function isValid($dateString, $format = 'Y-m-d') {
        return $dateString == self::fromYMDString($dateString)->toString($format);
    }

    /**
     * @return bool
     */
    public function isInFuture() {
        $now = self::now();
        return self::compareDates($this, $now) > 0;
    }

    /**
     * @return bool
     */
    public function isInPast() {
        $now = self::now();
        return self::compareDates($now, $this) > 0;
    }

    /**
     * @return bool
     */
    public function isToday() {
        $my = $this->toYMDString();
        $now = date('Y-m-d');
        $result = $my == $now;
        return $result;
    }


    /**
     * @static
     * @param DateAndTime $from
     * @param DateAndTime $to
     * @return int Returns &lt;0 if $from is less than $to; &gt;0 if $from is greater than $to; and 0 if they are equal
     */
    public static function compareDates(DateAndTime $from, DateAndTime $to) {
        return $from->getTimestamp() - $to->getTimestamp();
    }

}
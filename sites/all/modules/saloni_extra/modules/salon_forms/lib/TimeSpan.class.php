<?php

/**
 * Class TimeSpan
 */
class TimeSpan {

    private $_timestamp;

    /**
     * @return TimeSpan
     */
    public static function zero() {
        return new TimeSpan(0);
    }

    /**
     * @param $hours
     * @param $minutes
     * @param $seconds
     * @return TimeSpan
     */
    public static function create($hours, $minutes, $seconds) {
        $ts = $seconds
            + 60 * $minutes
            + 60 * 60 * $hours;
        return new TimeSpan($ts);
    }

    /**
     * @return TimeSpan
     */
    public static function fromString($txt) {
        $res = null;
        TimeSpan::parseFromString($txt, $res);
        return $res;
    }
    
    public static function parseFromString($txt, &$res) {
        $res = new TimeSpan(0);
        $arr = array();
        if ( preg_match('/(?P<sign>-?)(?P<h>\d{1,2}):(?P<m>\d{1,2}):?(?P<s>\d{1,2})?/', $txt, $arr) ) {
            $sign = (array_key_exists('sign', $arr) && $arr['sign'] === '-') ? -1 : 1;
            $h = array_key_exists('h', $arr) ? $arr['h'] : 0;
            $m = array_key_exists('m', $arr) ? $arr['m'] : 0;
            $s = array_key_exists('s', $arr) ? $arr['s'] : 0;
            $res->addHours($sign*$h);
            $res->addMinutes($sign*$m);
            $res->addSeconds($sign*$s);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @return TimeSpan
     */
    public static function fromSeconds($seconds) {
        return new TimeSpan($seconds);
    }

    /**
     * @return TimeSpan
     */
    public static function fromMinutes($minutes) {
        return new TimeSpan($minutes * 60);
    }

    /**
     * @param $ts
     */
    private function  __construct($ts) {
        $this->_timestamp = intval($ts);
    }


    /**
     * @return int
     */
    public function totalSeconds() {
        return $this->_timestamp;
    }

    /**
     * @return float
     */
    public function totalMinutes() {
        return $this->_timestamp / 60;
    }

    /**
     * @return float
     */
    public function totalHours() {
        return $this->_timestamp / 3600;
    }

    /**
     * @return float
     */
    public function totalDays() {
        return $this->_timestamp / (3600 * 24);
    }

    /**
     * @return int
     */
    public function seconds() {
        $sign = $this->isNegative() ? -1 : 1;
        $x = $sign*floor(abs($this->totalSeconds())) % 60;
        return $x;
    }

    /**
     * @return int
     */
    public function minutes() {
        $sign = $this->isNegative() ? -1 : 1;
        $x = $sign*floor(abs($this->totalMinutes())) % 60;
        return $x;
    }

    /**
     * @return int
     */
    public function hours() {
        $sign = $this->isNegative() ? -1 : 1;
        $x = $sign*floor(abs($this->totalHours()));
        return $x;
    }

    /**
     * @return bool
     */
    public function isNegative() {
        return $this->_timestamp < 0;
    }

    /**
     * @return bool
     */
    public function isZero() {
        return $this->_timestamp == 0;
    }

    /**
     * @return bool
     */
    public function isPositive() {
        return $this->_timestamp > 0;
    }

    /**
     * @return string
     */
    public function toString() {
        $x = sprintf("%s%02d:%02d:%02d", $this->isNegative() ? '-' : '', abs($this->hours()), abs($this->minutes()), abs($this->seconds()));
        return $x;
    }

    /**
     * @return string
     */
    public function toHMString() {
        $x = sprintf("%s%02d:%02d", $this->isNegative() ? '-' : '', abs($this->hours()), abs($this->minutes()));
        return $x;
    }

    /**
     * @return string
     */
    public function toHMSString() {
        $x = $this->toString();
        return $x;
    }


    /**
     * @param $value
     * @return $this
     */
    public function add($value) {
        if (is_string($value)) {
            $value = TimeSpan::fromString($value);
        }
        if ($value instanceof TimeSpan) {
            $this->_timestamp += $value->_timestamp;
        } else {
            $x  = intval($value);
            $this->_timestamp += $x;
        }
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function subtract($value) {
        if (is_string($value)) {
            $value = TimeSpan::fromString($value);
        }
        if ($value instanceof TimeSpan) {
            $this->_timestamp -= $value->_timestamp;
        } else {
            $x  = intval($value);
            $this->_timestamp -= $x;
        }
        return $this;
    }

    /**
     * @param $v
     * @return $this
     */
    public function addHours($v) {
        $this->_timestamp += floatval($v) * 3600;
        return $this;
    }

    /**
     * @param $v
     * @return $this
     */
    public function addMinutes($v) {
        $this->_timestamp += floatval($v) * 60;
        return $this;
    }

    /**
     * @param $v
     * @return $this
     */
    public function addSeconds($v) {
        $this->_timestamp += floatval($v);
        return $this;
    }

    /**
     * @param TimeSpan $compareTo
     * @return bool
     */
    public function greaterThan(TimeSpan $compareTo) {
        return $this->totalSeconds() > $compareTo->totalSeconds();
    }


    /**
     * @param TimeSpan $compareTo
     * @return bool
     */
    public function greaterOrEqualThan(TimeSpan $compareTo) {
        return $this->totalSeconds() >= $compareTo->totalSeconds();
    }

    /**
     * @param TimeSpan $compareTo
     * @return bool
     */
    public function lessThan(TimeSpan $compareTo) {
        return $this->totalSeconds() < $compareTo->totalSeconds();
    }

    /**
     * @param TimeSpan $compareTo
     * @return bool
     */
    public function lessOrEqualThan(TimeSpan $compareTo) {
        return $this->totalSeconds() <= $compareTo->totalSeconds();
    }


    /**
     * @param TimeSpan $compareTo
     * @return bool
     */
    public function equalAs(TimeSpan $compareTo) {
        return $this->totalSeconds() == $compareTo->totalSeconds();
    }

    /**
     * @param TimeSpan $from
     * @param TimeSpan $to
     * @return bool
     */
    public function isBetween(TimeSpan $from, TimeSpan $to) {
        return $this->greaterOrEqualThan($from) && $this->lessOrEqualThan($to);
    }

    /**
     * @return mixed
     */
    public function isValid() {
        $dayStart = self::create(0, 0, 0);
        $dayEnd = self::create(23, 59, 59);
        return $this->isBetween($dayStart, $dayEnd);
    }
}


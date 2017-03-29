<?php

include __DIR__ . '../lib/TimeSpan.class.php';

class SalonTimeSpanTable {

    const MIN_30 = 30;
    const MIN_20 = 20;

    /**
     * @var
     */
    private $start_time;
    /**
     * @var
     */
    private $end_time;

    /**
     * @var
     */
    private $step;

    /**
     * @var string
     */
    private $stringToPrepend = '';

    /**
     * @param $start
     * @param $end
     * @param $step
     */
    public function __construct($start = 0, $end = 24, $step = self::MIN_30) {
        $this->start_time = $start;
        $this->end_time = $end;
        $this->step = $step;
    }

    public function getHtml($data = [], $header = [], $table_id = 'timespan-table') {
        $this->prepareHeader($header);
        $preparedData = $this->prepareData($data);
        return theme(
            'table',
            array('header' => $header, 'rows' => $preparedData, 'attributes' => array('id' => $table_id))
        );
    }

    /**
     * @param $string
     */
    public function prependStringToTimeColumn($string) {
        $this->stringToPrepend = $string . ' ';
    }

    /**
     * @param $header
     */
    private function prepareHeader(&$header) {
        if (count($header)) {
            array_unshift($header, t('Time'));
        }
    }

    /**
     * @param $currentTimeSpan
     * @param $data
     * @return array
     */
    private function getPreparedRow(TimeSpan $currentTimeSpan, $data) {
        return array_merge(
            array($this->stringToPrepend . $currentTimeSpan->toHMString()),
            $data
        );
    }

    /**
     * @param $data array
     * @return array
     */
    private function  prepareData(array $data) {
        $preparedDataArray = [];
        $timeSpanArray = $this->createTimeSpanArray();
        $dataFormat = array_fill(0, count(reset($data)), null);
        foreach($timeSpanArray as $timeSpan) {
            $preparedDataArray[$timeSpan->toString()] = $this->getPreparedRow($timeSpan, $dataFormat);
        }
        $timeSpansTmp = array_keys($preparedDataArray);
        $i= 0;
        foreach($timeSpansTmp as $currentTimeSpan) {
            $current = TimeSpan::fromString($currentTimeSpan);
            foreach($data as $dataTime => $element) {
                $time = TimeSpan::fromString($dataTime);
                if (count($timeSpansTmp) > $i + 1) {
                    $next = TimeSpan::fromString($timeSpansTmp[$i+1]);
                    if ($current->lessOrEqualThan($time) && $next->greaterThan($time)) {
                        $preparedDataArray[$currentTimeSpan] = $this->getPreparedRow($current, $element);
                    }
                } else {
                    if ($current->lessOrEqualThan($time)) {
                        $preparedDataArray[$currentTimeSpan] = $this->getPreparedRow($current, $element);
                    }
                }
            }
            $i++;
        }
        return $preparedDataArray;
    }

    /**
     * @return TimeSpan[]
     */
    public function createTimeSpanArray() {
        $times = array();
        for ($h = $this->start_time; $h < $this->end_time; $h++){
            for ($m = 0; $m < 60 ; $m += $this->step){
                $times[] = TimeSpan::create((int)$h, (int)$m, 0);
            }
        }
        return $times;
    }
}
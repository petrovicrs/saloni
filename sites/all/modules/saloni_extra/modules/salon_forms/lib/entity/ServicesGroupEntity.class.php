<?php

/**
 * Class ServicesGroupEntity
 */
class ServicesGroupEntity extends SaloonEntity {

    /**
     * @var
     */
    private static $cached_table;

    /**
     * @var array
     */
    private $fields = ['id', 'opis', 'tip_grupa_usluga'];

    /**
     * @return ServicesGroupEntity
     */
    public static function create() {
        return new self('salon_grupa_usluga');
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    protected function validate($data) {}

    /**
     * @param array $data
     * @param null $id
     * @return mixed
     */
    protected function additionalPrepare(array &$data, $id = null){
        foreach($data as $fieldName => $fieldValue) {
            if (!in_array($fieldName, array_merge($this->fields, $this->mandatoryFields))) {
                unset($data[$fieldName]);
            }
        }
        return $data;
    }

    /**
     * @param $employeeTypeId
     * @return null
     */
    public function getServicesGroupName($employeeTypeId) {
        if (!isset(self::$cached_table)) {
            $this->cacheServicesGroup();
        }
        return isset(self::$cached_table[$employeeTypeId]) ? self::$cached_table[$employeeTypeId] : null;
    }

    /**
     *
     */
    private function cacheServicesGroup() {
        self::$cached_table = parent::getColWithIds('opis');
    }

}
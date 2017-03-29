<?php

/**
 * Class ServicesGroupTypeEntity
 */
class ServicesGroupTypeEntity extends SaloonEntity {

    /**
     * @var
     */
    private static $cached_table;

    /**
     * @var array
     */
    private $fields = ['id', 'naziv'];

    /**
     * @return ServicesGroupTypeEntity
     */
    public static function create() {
        return new ServicesGroupTypeEntity('salon_tip_usluga');;
    }

    /**
     * @param $employeeTypeId
     * @return null
     */
    public function getServicesGroupTypeName($employeeTypeId) {
        if (!isset(self::$cached_table)) {
            $this->cacheServicesGroupType();
        }
        return isset(self::$cached_table[$employeeTypeId]) ? self::$cached_table[$employeeTypeId] : null;
    }

    /**
     *
     */
    private function cacheServicesGroupType() {
        self::$cached_table = parent::getColWithIds('naziv');
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
}
<?php

/**
 * Class EmployeeTypeEntity
 */
class EmployeeTypeEntity extends SaloonEntity {

    /**
     * @var
     */
    private static $cached_table;

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    protected function validate($data) {}

    /**
     * @return EmployeeTypeEntity
     */
    public static function create() {
        return new EmployeeTypeEntity('salon_tip_zaposlenja');
    }


    /**
     * @param $employeeTypeId
     * @return null
     */
    public function getEmployeeTypeName($employeeTypeId) {
        if (!isset(self::$cached_table)) {
            $this->cacheEmployeeType();
        }
        return isset(self::$cached_table[$employeeTypeId]) ? self::$cached_table[$employeeTypeId] : null;
    }

    /**
     *
     */
    private function cacheEmployeeType() {
        self::$cached_table = parent::getColWithIds('naziv');
    }

    /**
     * @param array $data
     * @param null $id
     * @return mixed
     */
    protected function additionalPrepare(array &$data, $id = null) {
        return $data;
    }
}
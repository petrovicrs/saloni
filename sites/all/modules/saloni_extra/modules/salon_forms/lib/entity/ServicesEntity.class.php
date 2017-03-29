<?php

/**
 * Class ServicesEntity
 */
class ServicesEntity extends SaloonEntity {

    /**
     * @var array
     */
    private $fields = ['id', 'opis', 'grupa_usluga', 'opis', 'cena', 'kod'];

    /**
     * @return EmployeeEntity
     */
    public static function create() {
        return new self('salon_usluga');
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
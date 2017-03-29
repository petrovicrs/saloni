<?php

/**
 * Class EmployeeEntity
 */
class EmployeeEntity extends SaloonEntity {

    /**
     * @var array
     */
    private $fields = ['id', 'ime', 'prezime', 'jmbg', 'brlk', 'adresa', 'br_telefona', 'br_mobilnog_telefona', 'email',
        'procenat', 'tip_zaposlenog'];

    /**
     * @return EmployeeEntity
     */
    public static function create() {
        return new self('salon_zaposleni');
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
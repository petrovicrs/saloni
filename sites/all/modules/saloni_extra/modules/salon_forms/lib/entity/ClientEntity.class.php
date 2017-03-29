<?php

/**
 * Class EmployeeEntity
 */
class ClientEntity extends SaloonEntity {

    /**
     * @var array
     */
    private $fields = ['id', 'ime', 'prezime', 'adresa', 'br_telefona', 'br_mobilnog_telefona', 'email',
        'popust', 'napomena', 'datum_rodjenja', 'datum_registrovanja'];

    /**
     * @return EmployeeEntity
     */
    public static function create() {
        return new self('salon_klijent');
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
        /** set datum registrovanja za novog korisnika */
        if (!isset($id)) {
            $data['datum_registrovanja'] = DateAndTime::now()->toString();
        }
        return $data;
    }
}
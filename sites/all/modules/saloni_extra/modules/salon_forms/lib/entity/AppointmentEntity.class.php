<?php

/**
 * Class AppointmentEntity
 */
class AppointmentEntity extends SaloonEntity {

    /**
     * @var array
     */
    private $fields = ['id', 'id_klijent', 'id_zaposlenog', 'id_usluge', 'termin_od', 'termin_do', 'datum_rezervacije',
        'datum_termina', 'cena', 'cena_bez_popusta'];

    /**
     * @return EmployeeEntity
     */
    public static function create() {
        return new self('salon_termin');
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    protected function validate($data) {
        if (!isset($data['id_usluge']) || !isset($data['id_zaposlenog'])) {
            throw new ErrorException(t('There is no service or employee passed'));
        }
    }

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
        /** datum rezervacije novog termina */
        if (!isset($id)) {
            $data['datum_rezervacije'] = DateAndTime::now()->toString();
        }
        if (isset($data['id_usluge']) && isset($data['id_zaposlenog'])) {
            $service = ServicesEntity::create()->get($data['id_usluge']);
            $client = ClientEntity::create()->get($data['id_zaposlenog']);
            $data['cena'] = $service->cena;
            $popust = $client->popust / 100;
            $data['cena_bez_popusta'] = $service->cena * $popust;
        }
        if (isset($data['termin_od'])) {
            $data['termin_od'] = TimeSpan::fromString($data['termin_od'])->totalSeconds();
        }
        if (isset($data['termin_do'])) {
            $data['termin_do'] = TimeSpan::fromString($data['termin_do'])->totalSeconds();
        }
        return $data;
    }
}
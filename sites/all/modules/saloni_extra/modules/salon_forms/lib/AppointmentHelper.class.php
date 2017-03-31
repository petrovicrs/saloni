<?php

/**
 * Class AppointmentHelper
 */
class AppointmentHelper {

    /**
     * @param array $appointments
     * @param int $step
     * @return array
     */
    public static function prepareAppointments($appointments, $step) {
        $clients = ClientEntity::create()->getCols(array('id', 'ime', 'prezime'));
        $preparedClient = array();
        foreach($clients as $client) {
            $preparedClient[$client->id] = $client->ime . ' ' . $client->prezime;
        }
        $data = array();
        foreach ($appointments as $appointment) {
            $stepSeconds = TimeSpan::fromMinutes($step);
            for ($s = (int)$appointment->termin_od; $s < (int)$appointment->termin_do; $s += $stepSeconds->totalSeconds()) {
                $time = TimeSpan::fromSeconds($s)->toHMString();
                $employee = isset($preparedClient[$appointment->id_klijent]) ? $preparedClient[$appointment->id_klijent] : '';
                $data[$time] = array($employee);
            }
        }
        $header = [t('Klijent')];
        return array($data, $header);
    }

}
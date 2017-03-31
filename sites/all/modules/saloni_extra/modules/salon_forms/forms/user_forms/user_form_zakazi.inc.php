<?php

include drupal_get_path('module', 'salon_forms') . '/lib/TimeSpan.class.php';

define('PAGE_PATH', '/zakazi-termin');

function salon_forms_zakazi_form($form, &$form_state) {
    __add_zakazi_js();
    $employees = EmployeeEntity::create()->getCols(array('id', 'ime', 'prezime'));
    $selectEmployees = [];
    foreach ($employees as $employee) {
        $selectEmployees[$employee->id] = $employee->ime . ' ' . $employee->prezime;
    }
    $form = array();
    $form['klijent'] = array(
        '#type' => 'textfield',
        '#title' => t('Klijent'),
    );
    $form['id_klijent'] = array(
        '#type' => 'hidden',
        '#autocomplete_path' => 'klijenti/autocomplete',
    );
    $form['grupa_usluga'] = array(
        '#type' => 'select',
        '#title' => t('Grupa usluga'),
        '#options' => ServicesGroupEntity::create()->getColWithIds('opis'),
        '#required' => TRUE,
    );
    $form['id_usluge'] = array(
        '#type' => 'select',
        '#title' => t('Usluga'),
        '#options' => array(),
        '#required' => TRUE,
        '#validated' => TRUE,
    );
    $form['id_zaposlenog'] = array(
        '#type' => 'select',
        '#title' => t('Zaposleni'),
        '#options' => $selectEmployees,
        '#required' => TRUE,
    );
    $form['datum_termina'] = array(
        '#type' => 'date_popup',
        '#title' => t('Datum zakazivanja'),
        '#date_timezone' => date_default_timezone(),
        '#date_format' => 'd.m.Y',
        '#date_year_range' => '-3:+3',
        '#default_value' => null,
        '#date_label_position' => 'within',
        '#required' => TRUE,
    );
    $form['termin_od'] = array(
        '#type' => 'textfield',
        '#title' => t('Termin od'),
        '#attributes' => array('class' => array('timespan-from'), 'maxlength' => 15, 'size' => 15),
        '#date_timezone' => date_default_timezone(),
        '#required' => TRUE,
    );
    $form['termin_do'] = array(
        '#type' => 'textfield',
        '#title' => t('Termin do'),
        '#attributes' => array('class' => array('timespan-to'), 'maxlength' => 15, 'size' => 15),
        '#date_timezone' => date_default_timezone(),
        '#required' => TRUE,
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
        '#submit' => array('salon_forms_zakazi_form_submit')
    );
    return $form;
}

function salon_forms_zakazi_form_validate($form, &$form_state) {
    if (empty($form_state['values']['id_klijent']) || !is_numeric($form_state['values']['id_klijent'])) {
        form_set_error('id_klijent', t('Niste odabrali klijenta'));
    } else {
        $client = ClientEntity::create()->get((int)$form_state['values']['id_klijent']);
        if (!$client) {
            form_set_error('id_klijent', t('Odabrali ste nepostojeceg klijenta'));
        }
    }
    if (empty($form_state['values']['id_usluge']) || !is_numeric($form_state['values']['id_usluge'])) {
        form_set_error('id_usluge', t('Niste odabrali uslugu'));
    } else {
        $service = ServicesEntity::create()->get((int)$form_state['values']['id_usluge']);
        if (!$service) {
            form_set_error('id_usluge', t('Odabrali ste nepostojecu uslugu'));
        }
    }
    if (empty($form_state['values']['id_zaposlenog']) || !is_numeric($form_state['values']['id_zaposlenog'])) {
        form_set_error('id_zaposlenog', t('Niste odabrali zaposlenog'));
    } else {
        $employee = EmployeeEntity::create()->get((int)$form_state['values']['id_zaposlenog']);
        if (!$employee) {
            form_set_error('id_zaposlenog', t('Odabrali ste nepostojeceg zaposlenog'));
        }
    }
    if (empty($form_state['values']['datum_termina'])) {
        form_set_error('datum_termina', t('Niste odabrali datum termina'));
    } else {
        if (!DateAndTime::isValid($form_state['values']['datum_termina'])) {
            form_set_error('datum_termina', t('Uneli ste nevalidan datum'));
        }
        $date = DateAndTime::fromYMDString($form_state['values']['datum_termina']);
        if (!$date->isToday() && $date->isInPast()) {
            form_set_error('datum_termina', t('Uneli ste datum u proslosti'));
        }
    }
    if (empty($form_state['values']['termin_od']) || empty($form_state['values']['termin_do'])) {
        $element = empty($form_state['values']['termin_od']) ? 'termin_od' : 'termin_do';
        form_set_error($element, t('Morate odabrati i termin od i termin do.'));
    } else {
        $timeFrom = TimeSpan::fromString($form_state['values']['termin_od']);
        $timeTo = TimeSpan::fromString($form_state['values']['termin_do']);
        if (!$timeFrom->isValid()) {
            form_set_error('termin_od', t('Termin od ima nevalidnu vrednost.'));
        }
        if (!$timeTo->isValid()) {
            form_set_error('termin_do', t('Termin do ima nevalidnu vrednost.'));
        }
        if ($timeFrom->greaterOrEqualThan($timeTo)) {
            form_set_error('termin_do', t('Termin od mora biti manji od termina do.'));
        }
        $date = DateAndTime::fromYMDString($form_state['values']['datum_termina']);
        $appointments = AppointmentEntity::create()->getCols(array('termin_od', 'termin_do', 'id'), 'datum_termina = \''.$date->toString().'\'');
        $exist = false;
        foreach($appointments as $appointment) {
            $appointmentFrom = TimeSpan::fromSeconds($appointment->termin_od);
            $appointmentTo = TimeSpan::fromSeconds($appointment->termin_do);
            if ($timeFrom->isBetween($appointmentFrom, $appointmentTo) || $timeTo->isBetween($appointmentFrom, $appointmentTo)) {
                $exist = true;
                break;
            }
        }
        if ($exist) {
            form_set_error('termin_do', t('Za odabrani datum vec postoji termin u izabrano vreme.'));
        }
    }
    return $form;
}

function salon_forms_zakazi_form_submit($form, &$form_state) {
    $form_state['redirect'] = '/';
    AppointmentEntity::create()->save($form_state['values']);
    drupal_set_message(t('Uspesno ste sacuvali termin.'));
    return $form;
}

function __add_zakazi_specific_interval_js($from, $to, $interval) {
    drupal_add_js(
        array(
            'myTimespan' => array(
                'interval' => $interval,
                'from' => $from,
                'to' => $to,
            ),
        ),
        'setting'
    );
}

function __add_zakazi_js() {
    drupal_add_library('system', 'ui.autocomplete');
    drupal_add_css('sites/all/libraries/timepicker/jquery.timepicker.css');
    drupal_add_js('sites/all/libraries/timepicker/jquery.timepicker.min.js');
    drupal_add_js(drupal_get_path('module', 'salon_forms') . '/scripts/zakazi_form.js');
    $results = ClientEntity::create()->getCols(array('id', 'ime', 'prezime'));
    $clients = $clientsToId = [];
    foreach ($results as $result) {
        $clientName = $result->ime . ' ' . $result->prezime;
        $clients[] = $clientName;
        $clientsToId[$clientName] = $result->id;
    }
    drupal_add_js(
        array(
            'clients' => array(
                'availableClients' => json_encode($clients),
                'availableClientsIds' => json_encode($clientsToId),
                ),
            ),
        'setting'
    );
}
<?php

include drupal_get_path('module', 'salon_forms') . '/forms/user_forms/user_form_zakazi.inc.php';

function salon_forms_sisanje() {
    $page = array();
    $conf = getConfigByType(TYPE_FRIZER);
    $table = new SalonTimeSpanTable($conf['od'], $conf['do'], $conf['interval']);
    $date = getDateFromParameters();
    $dateString = $date->toString();
    $appointments = AppointmentEntity::create()->getCols(array('id', 'termin_od', 'termin_do', 'id_klijent'), ' datum_termina = \''.$dateString.'\' AND id_usluge=\'1\' ');
    list($data, $header) = AppointmentHelper::prepareAppointments($appointments, $conf['interval']);
    $page['date'] = array(
        '#markup' => $date->toDMYString()
    );
    $page['table'] = array(
        '#markup' => $table->getHtml($data, $header)
    );
    return $page;
}

function salon_forms_sisanje_zakazi() {
    $page = [];
    $conf = getConfigByType(TYPE_FRIZER);
    __add_zakazi_specific_interval_js($conf['od'], $conf['do'], $conf['interval']);
    $serviceGroup = ServicesGroupEntity::create()->getColWithIds('opis', ' tip_grupa_usluga = \'1\' ');
    $serviceGroup = array('' => '- Select -') + $serviceGroup;
    $form = drupal_get_form('salon_forms_zakazi_form');
    unset($form['grupa_usluga']['#options']);
    $form['grupa_usluga']['#options'] = $serviceGroup;
    $page['form'] = $form;
    return $page;
}
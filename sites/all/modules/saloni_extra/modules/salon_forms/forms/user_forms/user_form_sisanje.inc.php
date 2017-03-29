<?php

include drupal_get_path('module', 'salon_forms') . '/forms/user_forms/user_form_zakazi.inc.php';

function salon_forms_sisanje() {
    $page = array();
    includeModalWindowSettings();
    $table = new SalonTimeSpanTable(9, 22, SalonTimeSpanTable::MIN_30);
    $data = ['08:00' => array('a'), '21:00' => array('b')];
    $header = ['test'];
    $date = getDateFromParameters();
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
    $conf = getConfigByType('frizer');
    __add_zakazi_specific_interval_js($conf['od'], $conf['do'], $conf['interval']);
    $serviceGroup = ServicesGroupEntity::create()->getColWithIds('opis', ' tip_grupa_usluga = \'1\' ');
    $serviceGroup = array('' => '- Select -') + $serviceGroup;
    $form = drupal_get_form('salon_forms_zakazi_form');
    $form['grupa_usluga']['#option'] = $serviceGroup;
    $page['form'] = $form;
    return $page;
}
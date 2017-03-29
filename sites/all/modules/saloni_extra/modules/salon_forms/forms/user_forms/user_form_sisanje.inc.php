<?php

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
    dpm('here');
    return $page;
}
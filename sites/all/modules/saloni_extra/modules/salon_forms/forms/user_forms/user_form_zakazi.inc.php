<?php

define('PAGE_PATH', '/zakazi-termin');

function salon_forms_sisanje_zakazi_form($form, &$form_state) {
    drupal_add_library('system', 'ui.autocomplete');
    drupal_add_js(drupal_get_path('module', 'salon_forms') . '/scripts/zakazi.js');
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
        '#options' => array(),//ServicesEntity::create()->getColWithIds('opis'),
        '#required' => TRUE,
    );
    $form['id_zaposlenog'] = array(
        '#type' => 'select',
        '#title' => t('Grupa usluga'),
        '#options' => ServicesGroupEntity::create()->getColWithIds('opis'),
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
    );
    $form['termin_do'] = array(
        '#type' => 'textfield',
        '#title' => t('Termin do'),
        '#attributes' => array('class' => array('timespan-to'), 'maxlength' => 15, 'size' => 15),
        '#date_timezone' => date_default_timezone(),
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
    );
    return $form;
}

function salon_forms_sisanje_zakazi_form_submit($form, &$form_state) {
    dpm('submitted');
}
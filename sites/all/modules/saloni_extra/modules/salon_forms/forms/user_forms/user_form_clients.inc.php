<?php

define('PAGE_PATH', '/klijenti');

function salon_forms_clients() {
//    includeModalWindowSettings();
    $clients = ClientEntity::create()->getAll();
    $values = [];
    foreach ($clients as $client) {
        $values[] = array(
            $client->ime,
            $client->prezime,
            $client->adresa,
            $client->br_telefona,
            $client->br_mobilnog_telefona,
            $client->email,
            l(t('Edit'), PAGE_PATH . "/edit/{$client->id}")
            . '&emsp;' .
            l(t('Delete'), PAGE_PATH . "/delete/{$client->id}")
//            . '&emsp;' .
//            l(t('View'), PAGE_PATH . "/view/{$client->id}"),
        );
    }
    $page = [];
    $header = array(
        'ime' => t('Ime'),
        'prezime' => t('Prezime'),
        'adresa' => t('Adresa'),
        'br_tel' => t('Broj telefona'),
        'br_mobilnog' => t('Broj mobilnog telefona'),
        'email' => t('Email'),
        'akcije' => t('Akcije'),
    );
    $page['maintable'] = array(
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $values,
        '#empty' => t('No items available'),
    );
    return $page;
}

function salon_forms_clients_add($form, &$form_state) {
    $form['ime'] = array(
        '#type' => 'textfield',
        '#title' => t('Ime klijenta'),
        '#required' => TRUE,
    );
    $form['prezime'] = array(
        '#type' => 'textfield',
        '#title' => t('Prezime klijenta'),
        '#required' => TRUE,
    );
    $form['adresa'] = array(
        '#type' => 'textfield',
        '#title' => t('Adresa klijenta'),
    );
    $form['br_telefona'] = array(
        '#type' => 'textfield',
        '#title' => t('Br telefona klijenta'),
    );
    $form['br_mobilnog_telefona'] = array(
        '#type' => 'textfield',
        '#title' => t('Br mobilnog telefona klijenta'),
        '#required' => TRUE,
    );
    $form['email'] = array(
        '#type' => 'textfield',
        '#title' => t('Email klijenta'),
    );
    $form['datum_rodjenja'] = array(
        '#type' => 'date_popup',
        '#title' => t('Datum rodjenja'),
        '#date_timezone' => date_default_timezone(),
        '#date_format' => 'd.m.Y',
        '#date_year_range' => '-3:+3',
        '#default_value' => null,
        '#date_label_position' => 'within',
        '#required' => TRUE,
    );
    $form['popust'] = array(
        '#type' => 'textfield',
        '#attributes' => array('maxlength' => 3, 'size' => 4, 'class' => array('container-inline inline-field')),
        '#field_suffix' => '%',
        '#title' => t('Procenat zaposlenog'),

    );
    $form['napomena'] = array(
        '#title' => t('Napomena'),
        '#type' => 'textarea',
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save'),
        '#submit' => array('salon_forms_clients_add_submit')
    );
    return $form;
}

function salon_forms_clients_add_validate($form, &$form_state) {
    if (!empty($form_state['values']['procenat']) && !is_numeric($form_state['values']['procenat'])) {
        form_set_error('procenat', t('Proenat mora biti broj'));
    }
    if (!empty($form_state['values']['email']) && !filter_var($form_state['values']['email'], FILTER_VALIDATE_EMAIL)) {
        form_set_error('email', t('Nevalidan email'));
    }
    return $form;
}

function salon_forms_clients_add_submit($form, &$form_state) {
    $form_state['redirect'] = '/klijenti';
    ClientEntity::create()->save($form_state['values']);
    return $form;
}


function salon_forms_clients_edit($form, &$form_state, $id) {
    $client = ClientEntity::create()->get($id);
    if (!$client) {
        drupal_set_message(t('Unknown client'), 'notice');
        drupal_goto(PAGE_PATH);
    }
    $form['id'] = array(
        '#type' => 'hidden',
        '#value' => $id,
        '#default_value' => isset($client->id) && !empty($client->id) ? $client->id : '',
        '#required' => TRUE
    );

    $form['ime'] = array(
        '#type' => 'textfield',
        '#title' => t('Ime klijenta'),
        '#default_value' => isset($client->ime) && !empty($client->ime) ? $client->ime : '',
        '#required' => TRUE,
    );
    $form['prezime'] = array(
        '#type' => 'textfield',
        '#title' => t('Prezime klijenta'),
        '#default_value' => isset($client->prezime) && !empty($client->prezime) ? $client->prezime : '',
        '#required' => TRUE,
    );
    $form['adresa'] = array(
        '#type' => 'textfield',
        '#title' => t('Adresa klijenta'),
        '#default_value' => isset($client->adresa) && !empty($client->adresa) ? $client->adresa : '',
    );
    $form['br_telefona'] = array(
        '#type' => 'textfield',
        '#default_value' => isset($client->br_telefona) && !empty($client->br_telefona) ? $client->br_telefona : '',
        '#title' => t('Br telefona klijenta'),
    );
    $form['br_mobilnog_telefona'] = array(
        '#type' => 'textfield',
        '#title' => t('Br mobilnog telefona klijenta'),
        '#default_value' => isset($client->br_mobilnog_telefona) && !empty($client->br_mobilnog_telefona) ? $client->br_mobilnog_telefona : '',
        '#required' => TRUE,
    );
    $form['email'] = array(
        '#type' => 'textfield',
        '#default_value' => isset($client->email) && !empty($client->email) ? $client->email : '',
        '#title' => t('Email klijenta'),
    );
    $form['datum_rodjenja'] = array(
        '#type' => 'date_popup',
        '#title' => t('Datum rodjenja'),
        '#date_timezone' => date_default_timezone(),
        '#date_format' => 'd.m.Y',
        '#date_year_range' => '-3:+3',
        '#date_label_position' => 'within',
        '#default_value' => isset($client->datum_rodjenja) && !empty($client->datum_rodjenja) ? $client->datum_rodjenja : '',
        '#required' => TRUE,
    );
    $form['popust'] = array(
        '#type' => 'textfield',
        '#attributes' => array('maxlength' => 3, 'size' => 4, 'class' => array('container-inline inline-field')),
        '#field_suffix' => '%',
        '#default_value' => isset($client->popust) && !empty($client->popust) ? $client->popust : '',
        '#title' => t('Procenat zaposlenog'),

    );
    $form['napomena'] = array(
        '#title' => t('Napomena'),
        '#default_value' => isset($client->napomena) && !empty($client->napomena) ? $client->napomena : '',
        '#type' => 'textarea',
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save'),
    );
    return $form;
}

function salon_forms_clients_edit_validate($form, &$form_state) {
    if (!empty($form_state['values']['procenat']) && !is_numeric($form_state['values']['procenat'])) {
        form_set_error('procenat', t('Proenat mora biti broj'));
    }
    if (!empty($form_state['values']['email']) && !filter_var($form_state['values']['email'], FILTER_VALIDATE_EMAIL)) {
        form_set_error('email', t('Nevalidan email'));
    }
    return $form;
}

function salon_forms_clients_edit_submit($form, &$form_state) {
    $form_state['redirect'] = PAGE_PATH;
    $id = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$id || !EmployeeEntity::create()->get($id)) {
        drupal_set_message(t('Unknown client'), 'warning');
    }
    ClientEntity::create()->save($form_state['values'], $id);
    drupal_set_message(t('You have successfully changed client.'));
    return $form;
}

function salon_forms_clients_delete($form, &$form_state, $id) {
    $client = ClientEntity::create()->get($id);
    if (!$client) {
        drupal_set_message(t('Unknown client'), 'notice');
        drupal_goto(PAGE_PATH);
    }
    $form['id'] = array(
        '#type' => 'hidden',
        '#value' => $id,
        '#required' => TRUE
    );
    $form['fname_lname'] = array(
        '#markup' => '<div style="margin: 10px 0;">' . t('Are you sure you want to delete client') . ' - ' . $client->ime . ' ' . $client->prezime . '</div>'
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Delete'),
        '#submit' => array('salon_forms_clients_delete_submit')
    );
    return $form;
}

function salon_forms_clients_delete_submit($form, &$form_state) {
    $form_state['redirect'] = PAGE_PATH;
    $id = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$id || !ClientEntity::create()->get($id)) {
        drupal_set_message(t('Unknown employee'), 'warning');
    }
    ClientEntity::create()->delete($id);
    drupal_set_message(t('You have successfully deleted client.'));
    return $form;
}

function _client_autocomplete($string) {
    $matches = [];
    $results = ClientEntity::create()->getColsWithIdsWithCondition(array('id', 'ime', 'prezime'), 'ime LIKE \'%'.$string.'%\' OR prezime LIKE \'%'.$string.'%\'');
    foreach($results as $result) {
        $matches[$result->ime . ' ' . $result->prezime] = $result->ime . ' ' . $result->prezime;
    }
    drupal_json_output($matches);
}

<?php

define('PAGE_PATH', '/admin/saloon/services');

/**
 * @return array
 */
function salon_forms_admin_services() {
    $services = ServicesEntity::create()->getAll();
    $values = [];
    $servicesGroupEntity = ServicesGroupEntity::create();
    foreach($services as $service) {
        $values[] = array(
            $service->opis,
            $servicesGroupEntity->getServicesGroupName($service->grupa_usluga),
            $service->cena,
            $service->kod,
            l(t('Edit'), PAGE_PATH . "/edit/{$service->id}") . '&emsp;' . l(t('Delete'), PAGE_PATH . "/delete/{$service->id}"),
        );
    }
    $page = [];
    $header = array(
        'opis' => t('Opis'),
        'grupa_usluga' => t('Grupa usluga'),
        'cena' => t('Cena'),
        'kod' => t('Kod'),
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

function salon_forms_admin_services_add($form, &$form_state) {
    $form['opis'] = array(
        '#type' => 'textfield',
        '#title' => t('Opis'),
        '#required' => TRUE,
    );
    $form['cena'] = array(
        '#type' => 'textfield',
        '#title' => t('Cena'),
        '#required' => TRUE,
    );
    $form['kod'] = array(
        '#type' => 'textfield',
        '#title' => t('Kod'),
        '#required' => TRUE,
    );
    $form['grupa_usluga'] = array(
        '#type' => 'select',
        '#title' => t('Grupa usluga'),
        '#options' => ServicesGroupEntity::create()->getColWithIds('opis'),
        '#required' => TRUE,
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
        '#submit' => array('salon_forms_admin_services_add_submit')
    );
    return $form;
}

function salon_forms_admin_services_add_submit($form, &$form_state) {
    $form_state['redirect'] = PAGE_PATH;
    ServicesEntity::create()->save($form_state['values']);
    return $form;
}

function salon_forms_admin_services_edit($form, &$form_state, $id) {
    $entity = ServicesEntity::create()->get($id);
    if (!$entity) {
        drupal_set_message(t('Unknown employee'), 'notice');
        drupal_goto(PAGE_PATH);
    }
    $form['id'] = array(
        '#type' => 'hidden',
        '#value' => $id,
        '#required' => TRUE
    );
    $form['opis'] = array(
        '#type' => 'textfield',
        '#title' => t('Opis'),
        '#required' => TRUE,
        '#default_value' => isset($entity->opis) && !empty($entity->opis) ? $entity->opis : '',
    );
    $form['cena'] = array(
        '#type' => 'textfield',
        '#title' => t('Cena'),
        '#required' => TRUE,
        '#default_value' => isset($entity->cena) && !empty($entity->cena) ? $entity->cena : '',
    );
    $form['kod'] = array(
        '#type' => 'textfield',
        '#title' => t('Kod'),
        '#default_value' => isset($entity->kod) && !empty($entity->kod) ? $entity->kod : '',
    );
    $form['grupa_usluga'] = array(
        '#type' => 'select',
        '#title' => t('Grupa usluga'),
        '#options' => ServicesGroupEntity::create()->getColWithIds('opis'),
        '#required' => TRUE,
        '#default_value' => isset($entity->grupa_usluga) && !empty($entity->grupa_usluga) ? $entity->grupa_usluga : null,
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Edit'),
    );
    return $form;
}

function salon_forms_admin_services_edit_submit($form, &$form_state) {
    $form_state['redirect'] = PAGE_PATH;
    $id = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$id || !ServicesEntity::create()->get($id)) {
        drupal_set_message(t('Unknown service'), 'warning');
    }
    ServicesEntity::create()->save($form_state['values'], $id);
    drupal_set_message(t('You have successfully changed service.'));
    return $form;
}

function salon_forms_admin_services_delete($form, &$form_state, $id) {
    $entity = ServicesEntity::create()->get($id);
    if (!$entity) {
        drupal_set_message(t('Unknown service'), 'notice');
        drupal_goto(PAGE_PATH);
    }
    $form['id'] = array(
        '#type' => 'hidden',
        '#value' => $id,
        '#required' => TRUE
    );
    $form['name'] = array(
        '#markup' => '<div style="margin: 10px 0;">' . t('Are you sure you want to delete service') . ' - ' . $entity->opis . '</div>'
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Delete'),
        '#submit' => array('salon_forms_admin_services_delete_submit')
    );
    return $form;
}

function salon_forms_admin_services_delete_submit($form, &$form_state) {
    $form_state['redirect'] = PAGE_PATH;
    $id = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$id || !ServicesEntity::create()->get($id)) {
        drupal_set_message(t('Unknown service'), 'warning');
    }
    ServicesEntity::create()->delete($id);
    drupal_set_message(t('You have successfully deleted service.'));
    return $form;
}

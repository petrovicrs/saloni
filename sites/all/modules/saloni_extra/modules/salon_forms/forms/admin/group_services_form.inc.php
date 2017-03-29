<?php

/**
 * @return array
 */
function salon_forms_admin_group_services() {
    $servicesGroups = ServicesGroupEntity::create()->getAll();
    $values = [];
    $servicesGroupTypeEntity = ServicesGroupTypeEntity::create();
    foreach($servicesGroups as $servicesGroup) {
        $values[] = array(
            $servicesGroup->opis,
            $servicesGroupTypeEntity->getServicesGroupTypeName($servicesGroup->tip_grupa_usluga),
            l(t('Edit'), "/admin/saloon/services/groups/edit/{$servicesGroup->id}") . '&emsp;' . l(t('Delete'), "/admin/saloon/services/groups/delete/{$servicesGroup->id}"),
        );
    }
    $page = [];
    $header = array(
        'opis' => t('Opis'),
        'tip' => t('Tip usluge'),
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

function salon_forms_admin_group_services_add($form, &$form_state) {
    $form['opis'] = array(
        '#type' => 'textfield',
        '#title' => t('Opis'),
        '#required' => TRUE,
    );
    $form['tip_grupa_usluga'] = array(
        '#type' => 'select',
        '#title' => t('Tip usluge'),
        '#options' => ServicesGroupTypeEntity::create()->getColWithIds('naziv'),
        '#required' => TRUE,
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
        '#submit' => array('salon_forms_admin_group_services_add_submit')
    );
    return $form;
}

function salon_forms_admin_group_services_add_submit($form, &$form_state) {
    $form_state['redirect'] = '/admin/saloon/services/groups';
    ServicesGroupEntity::create()->save($form_state['values']);
    return $form;
}

function salon_forms_admin_group_services_edit($form, &$form_state, $id) {
    $serviceGroup = ServicesGroupEntity::create()->get($id);
    if (!$serviceGroup) {
        drupal_set_message(t('Unknown service group'), 'notice');
        drupal_goto('/admin/saloon/services/groups');
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
        '#default_value' => $serviceGroup->opis,
    );
    $form['tip_grupa_usluga'] = array(
        '#type' => 'select',
        '#title' => t('Tip usluge'),
        '#options' => ServicesGroupTypeEntity::create()->getColWithIds('naziv'),
        '#required' => TRUE,
        '#default_value' => $serviceGroup->tip_grupa_usluga,
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save edit'),
    );
    return $form;
}

function salon_forms_admin_group_services_edit_submit($form, &$form_state) {
    $form_state['redirect'] = '/admin/saloon/services/groups';
    $id = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$id || !ServicesGroupEntity::create()->get($id)) {
        drupal_set_message(t('Unknown service group'), 'warning');
    }
    ServicesGroupEntity::create()->save($form_state['values'], $id);
    drupal_set_message(t('You have successfully changed service group.'));
    return $form;
}

function salon_forms_admin_group_services_delete($form, &$form_state, $id) {
    $serviceGroup = ServicesGroupEntity::create()->get($id);
    if (!$serviceGroup) {
        drupal_set_message(t('Unknown service group'), 'notice');
        drupal_goto('/admin/saloon/services/groups');
    }
    $form['id'] = array(
        '#type' => 'hidden',
        '#value' => $id,
        '#required' => TRUE
    );
    $form['name'] = array(
        '#markup' => '<div style="margin: 10px 0;">' . t('Are you sure you want to delete service group') . ' - ' . $serviceGroup->opis . '</div>'
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Delete'),
        '#submit' => array('salon_forms_admin_group_services_delete_submit')
    );
    return $form;
}

function salon_forms_admin_group_services_delete_submit($form, &$form_state) {
    $form_state['redirect'] = '/admin/saloon/services/groups';
    $id = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$id || !ServicesGroupEntity::create()->get($id)) {
        drupal_set_message(t('Unknown service group'), 'warning');
    }
    ServicesGroupEntity::create()->delete($id);
    drupal_set_message(t('You have successfully deleted service group.'));
    return $form;
}

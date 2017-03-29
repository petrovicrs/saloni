<?php

/**
 * @return array
 */
function salon_forms_admin_employees() {
    $employees = EmployeeEntity::create()->getAll();
    $values = [];
    $employeeTypeEntity = EmployeeTypeEntity::create();
    foreach($employees as $employee) {
        $values[] = array(
            $employee->ime,
            $employee->prezime,
            $employee->adresa,
            $employee->br_telefona,
            $employee->br_mobilnog_telefona,
            $employee->email,
            $employeeTypeEntity->getEmployeeTypeName($employee->tip_zaposlenog),
            l(t('Edit'), "/admin/saloon/employees/edit/{$employee->id}") . '&emsp;' . l(t('Delete'), "/admin/saloon/employees/delete/{$employee->id}"),
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
        'tip' => t('Tip zaposlenog'),
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

function salon_forms_admin_employees_add($form, &$form_state) {
    $form['ime'] = array(
        '#type' => 'textfield',
        '#title' => t('Ime zaposlenog'),
        '#required' => TRUE,
    );
    $form['prezime'] = array(
        '#type' => 'textfield',
        '#title' => t('Prezime zaposlenog'),
        '#required' => TRUE,
    );
    $form['jmbg'] = array(
        '#type' => 'textfield',
        '#title' => t('JMBG zaposlenog'),
    );
    $form['brlk'] = array(
        '#type' => 'textfield',
        '#title' => t('Br. licne karte zaposlenog'),
    );
    $form['adresa'] = array(
        '#type' => 'textfield',
        '#title' => t('Adresa zaposlenog'),
    );
    $form['br_telefona'] = array(
        '#type' => 'textfield',
        '#title' => t('Br telefona zaposlenog'),
    );
    $form['br_mobilnog_telefona'] = array(
        '#type' => 'textfield',
        '#title' => t('Br mobilnog telefona zaposlenog'),
        '#required' => TRUE,
    );
    $form['email'] = array(
        '#type' => 'textfield',
        '#title' => t('Email zaposlenog'),
    );
    $form['procenat'] = array(
        '#type' => 'textfield',
        '#title' => t('Procenat zaposlenog'),
    );
    $form['tip_zaposlenog'] = array(
        '#type' => 'select',
        '#title' => t('Tip zaposlenog'),
        '#options' => EmployeeTypeEntity::create()->getColWithIds('naziv'),
        '#required' => TRUE,
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
        '#submit' => array('salon_forms_admin_employees_add_submit')
  );
  return $form;
}

function salon_forms_admin_employees_add_validate($form, &$form_state) {
    if (!empty($form_state['values']['jmbg']) && !is_numeric($form_state['values']['jmbg'])
        && strlen((string)$form_state['values']['jmbg'] != 13)
    ) {
        form_set_error('jmbg', t('JMBG mora biti broj sa 13 cifara'));
    }
    if (!empty($form_state['values']['procenat']) && !is_numeric($form_state['values']['procenat'])) {
        form_set_error('procenat', t('Proenat mora biti broj'));
    }
    if (!empty($form_state['values']['brlk']) && !is_numeric($form_state['values']['brlk'])) {
        form_set_error('brlk', t('Broj licne karte mora biti broj'));
    }
    $tipZaposlenja = EmployeeTypeEntity::create()->getColWithIds('naziv');
    if (!in_array(
        $form_state['values']['tip_zaposlenog'],
        array_keys($tipZaposlenja))
    ) {
        form_set_error('tip_zaposlenog', t('Tip zaposlenog mora biti jedan od: ') . implode(', ', $tipZaposlenja));
    }
    return $form;
}

function salon_forms_admin_employees_add_submit($form, &$form_state) {
    $form_state['redirect'] = '/admin/saloon/employees';
    EmployeeEntity::create()->save($form_state['values']);
    return $form;
}

function salon_forms_admin_employees_edit($form, &$form_state, $employeeId) {
    $employee = EmployeeEntity::create()->get($employeeId);
    if (!$employee) {
        drupal_set_message(t('Unknown employee'), 'notice');
        drupal_goto('/admin/saloon/employees');
    }
    $form['id'] = array(
        '#type' => 'hidden',
        '#value' => $employeeId,
        '#required' => TRUE
    );
    $form['ime'] = array(
        '#type' => 'textfield',
        '#title' => t('Ime zaposlenog'),
        '#required' => TRUE,
        '#default_value' => isset($employee->ime) && !empty($employee->ime) ? $employee->ime : '',
    );
    $form['prezime'] = array(
        '#type' => 'textfield',
        '#title' => t('Prezime zaposlenog'),
        '#required' => TRUE,
        '#default_value' => isset($employee->prezime) && !empty($employee->prezime) ? $employee->prezime : '',
    );
    $form['jmbg'] = array(
        '#type' => 'textfield',
        '#title' => t('JMBG zaposlenog'),
        '#default_value' => isset($employee->jmbg) && !empty($employee->jmbg) ? $employee->jmbg : '',
    );
    $form['brlk'] = array(
        '#type' => 'textfield',
        '#title' => t('Br. licne karte zaposlenog'),
        '#default_value' => isset($employee->brlk) && !empty($employee->brlk) ? $employee->brlk : '',
    );
    $form['adresa'] = array(
        '#type' => 'textfield',
        '#title' => t('Adresa zaposlenog'),
        '#default_value' => isset($employee->adresa) && !empty($employee->adresa) ? $employee->adresa : '',
    );
    $form['br_telefona'] = array(
        '#type' => 'textfield',
        '#title' => t('Br telefona zaposlenog'),
        '#default_value' => isset($employee->br_telefona) && !empty($employee->br_telefona) ? $employee->br_telefona : '',
    );
    $form['br_mobilnog_telefona'] = array(
        '#type' => 'textfield',
        '#title' => t('Br mobilnog telefona zaposlenog'),
        '#required' => TRUE,
        '#default_value' => isset($employee->br_mobilnog_telefona) && !empty($employee->br_mobilnog_telefona) ? $employee->br_mobilnog_telefona : '',
    );
    $form['email'] = array(
        '#type' => 'textfield',
        '#title' => t('Email zaposlenog'),
        '#default_value' => isset($employee->email) && !empty($employee->email) ? $employee->email : '',
    );
    $form['procenat'] = array(
        '#type' => 'textfield',
        '#title' => t('Procenat zaposlenog'),
        '#default_value' => isset($employee->procenat) && !empty($employee->procenat) ? $employee->procenat : '',
    );
    $form['tip_zaposlenog'] = array(
        '#type' => 'select',
        '#title' => t('Tip zaposlenog'),
        '#options' => EmployeeTypeEntity::create()->getColWithIds('naziv'),
        '#required' => TRUE,
        '#default_value' => isset($employee->tip_zaposlenog) && !empty($employee->tip_zaposlenog) ? $employee->tip_zaposlenog : null,
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Edit'),
    );
    return $form;
}

function salon_forms_admin_employees_edit_validate($form, &$form_state) {
    if (!empty($form_state['values']['jmbg']) && !is_numeric($form_state['values']['jmbg'])
        && strlen((string)$form_state['values']['jmbg'] != 13)
    ) {
        form_set_error('jmbg', t('JMBG mora biti broj sa 13 cifara'));
    }
    if (!empty($form_state['values']['procenat']) && !is_numeric($form_state['values']['procenat'])) {
        form_set_error('procenat', t('Proenat mora biti broj'));
    }
    if (!empty($form_state['values']['brlk']) && !is_numeric($form_state['values']['brlk'])) {
        form_set_error('brlk', t('Broj licne karte mora biti broj'));
    }
    $tipZaposlenja = EmployeeTypeEntity::create()->getColWithIds('naziv');
    if (!in_array(
        $form_state['values']['tip_zaposlenog'],
        array_keys($tipZaposlenja))
    ) {
        form_set_error('tip_zaposlenog', t('Tip zaposlenog mora biti jedan od: ') . implode(', ', $tipZaposlenja));
    }
    return $form;
}

function salon_forms_admin_employees_edit_submit($form, &$form_state) {
    $form_state['redirect'] = '/admin/saloon/employees';
    $employeeId = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$employeeId || !EmployeeEntity::create()->get($employeeId)) {
        drupal_set_message(t('Unknown employee'), 'warning');
    }
    EmployeeEntity::create()->save($form_state['values'], $employeeId);
    drupal_set_message(t('You have successfully changed employee.'));
    return $form;
}

function salon_forms_admin_employees_delete($form, &$form_state, $employeeId) {
    $employee = EmployeeEntity::create()->get($employeeId);
    if (!$employee) {
        drupal_set_message(t('Unknown employee'), 'notice');
        drupal_goto('/admin/saloon/employees');
    }
    $form['id'] = array(
        '#type' => 'hidden',
        '#value' => $employeeId,
        '#required' => TRUE
    );
    $form['fname_lname'] = array(
        '#markup' => '<div style="margin: 10px 0;">' . t('Are you sure you want to delete employee') . ' - ' . $employee->ime . ' ' . $employee->prezime . '</div>'
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Delete'),
        '#submit' => array('salon_forms_admin_employees_delete_submit')
    );
    return $form;
}

function salon_forms_admin_employees_delete_submit($form, &$form_state) {
    $form_state['redirect'] = '/admin/saloon/employees';
    $employeeId = isset($form_state['values']['id']) ? $form_state['values']['id'] : null;
    if (!$employeeId || !EmployeeEntity::create()->get($employeeId)) {
        drupal_set_message(t('Unknown employee'), 'warning');
    }
    EmployeeEntity::create()->delete($employeeId);
    drupal_set_message(t('You have successfully deleted employee.'));
    return $form;
}

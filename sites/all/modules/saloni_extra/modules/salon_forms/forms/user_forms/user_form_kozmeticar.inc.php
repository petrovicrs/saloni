<?php

function salon_forms_kozmeticar() {
    $page = array();
    $table = new SalonTimeSpanTable(9, 22, SalonTimeSpanTable::MIN_30);
    $data = ['09:00' => array('test a'), '21:00' => array('test')];
    $header = ['test'];
    $page['a'] = array(
        '#markup' => $table->getHtml($data, $header)
    );
    return $page;
}

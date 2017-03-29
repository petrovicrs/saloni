<?php

function salon_forms_masaza() {
  $page = array();
  $table = new SalonTimeSpanTable(9, 22, SalonTimeSpanTable::MIN_30);
  $data = ['02:00' => array('test a'), '03:30' => array('test')];
  $header = ['test'];
  $page['a'] = array(
      '#markup' => $table->getHtml($data, $header)
  );
  return $page;
}

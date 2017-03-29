<?php

function form_services_autocomplete() {
    $response = [];
    if(isset($_POST['groupId']) && !empty($_POST['groupId'])){
        $groupId = $_POST['groupId'];
        $response = ServicesEntity::create()->getColWithIds('opis', ' grupa_usluga = \''.$groupId.'\' ');
    }
    drupal_json_output($response);
}
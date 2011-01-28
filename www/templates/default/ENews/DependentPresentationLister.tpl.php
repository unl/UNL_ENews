<?php
//header('Content-type: application/json');
$json = array();
foreach ($context->presentation_list as $presentation) {
    $json[$presentation->id] = $presentation->dependent_selector;
}
echo json_encode($json, JSON_FORCE_OBJECT);

<?php
//header('Content-type: application/json');
$json = array();
foreach ($context as $presentation) {
    $json[$presentation->id] = $presentation->description;
}
echo json_encode($json, JSON_FORCE_OBJECT);
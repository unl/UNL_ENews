"stories":
<?php
$stories = array();
foreach ($context as $story) {
    $stories[] = $story->toArray();
}
echo json_encode($stories);
?>
"stories":
<?php
$stories = array();
foreach ($context as $story) {
    /* @var $story UNL_ENews_Story */
    $stories[$story->getURL()] = $story->toExtendedArray();
}
echo json_encode($stories);
?>
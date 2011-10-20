<?php
$storyFlag = false;

foreach ($context as $story) {
    $storyFlag = true;
    echo $savvy->render($story, 'ENews/Newsroom/UnpublishedStory.tpl.php');
}

if (!$storyFlag) {
    echo '<p>No Available Items</p>';
}
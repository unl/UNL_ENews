<?php

foreach ($context as $story) {
    echo $savvy->render($story, 'ENews/Newsroom/UnpublishedStory.tpl.php');
}

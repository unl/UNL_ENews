<?php
if (isset($context->getRawObject()->options['type'])) {
    $type = $context->getRawObject()->options['type'];
} else {
    $type = 'news';
}
$stories = new UNL_ENews_StoryList_Filter_ByPresentationType($context->getRawObject(), $type);
echo $savvy->render($stories);
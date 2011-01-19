<?php
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

if ($file = $context->getFileByUse('originalimage')) {
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'">';
    }

    echo '<img src="'.$file->getURL().'" style="width:556px;height:212px;margin-bottom:5px;" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
}
?>
<?php
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

if ($file = $context->getFileByUse('originalimage')) {
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'">';
    }

    echo '<img src="'.$file->getURL().'" style="width:536px;height:204px;margin-bottom:5px;" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
}
?>
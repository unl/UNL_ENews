<?php
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

if ($file = $context->getFileByUse('originalimage')) {
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'">';
    }

    echo '<img src="'.$file->getURL().'" style="width:536px;height:204px;padding:10px;border-top:1px solid #E0E0E0;border-bottom:1px solid #E0E0E0;" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
}
?>
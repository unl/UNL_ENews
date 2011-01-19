<?php 
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

if ($file = $context->getFileByUse('originalimage')) {
    echo '<div style="background:#f0f0f0;padding:10px;margin-top:10px;margin-bottom:10px;border-top:1px solid #E0E0E0;border-bottom:1px solid #E0E0E0;">';
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'">';
    }

    echo '<img src="'.$file->getURL().'" style="width:253px;height:96px;" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
    echo '</div>';
}
?>
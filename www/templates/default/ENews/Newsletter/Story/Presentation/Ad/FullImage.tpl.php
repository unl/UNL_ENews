<?php
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

$width = $context->getColFromSort() == 'twocol' ? 680 : 320;

if ($file = $context->getFileByUse('originalimage')) {
    echo '<div style="background:#f4f2cc;padding:10px">';
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'">';
    }

    echo '<img src="'.$file->getURL().'" style="width:'. $width .'px;margin-bottom:5px;" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
    echo '</div>';
}
?>
<?php 
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

if ($file = $context->getFileByUse('originalimage')) {
    echo '<div style="background:#f4f2cc;padding:10px;">';
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'">';
    }

    echo '<img src="'.UNL_ENews_Controller::getURL().'file'
         . $file->id
         . '.jpg" style="width:253px;height:96px;" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
    echo '</div>';
}
?>
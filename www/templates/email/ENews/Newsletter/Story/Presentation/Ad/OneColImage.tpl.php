<?php 
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

if ($file = $context->getFileByUse('originalimage')) {
    echo '<div style="background:#fbf9d8;padding:10px;margin-top:10px;margin-bottom:10px;border-top:1px solid #e6e182;border-bottom:1px solid #e6e182;">';
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
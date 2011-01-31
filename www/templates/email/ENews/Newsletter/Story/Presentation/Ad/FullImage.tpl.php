<?php
if (!$context->isWithinRequestedPublishDate()) {
    return;
}

if ($context->getColFromSort() == 'twocol') {
    $fileDef = array(
        'use'    => UNL_ENews_File_Image::MAX_WIDTH.'_wide',
        'width'  => UNL_ENews_File_Image::FULL_AD_WIDTH,
        'height' => UNL_ENews_File_Image::FULL_AD_HEIGHT
    );
} else {
    $fileDef = array(
        'use'    => UNL_ENews_File_Image::HALF_WIDTH.'_wide',
        'width'  => UNL_ENews_File_Image::HALF_AD_WIDTH,
        'height' => UNL_ENews_File_Image::HALF_AD_HEIGHT
    );
}

if ($file = $context->getFileByUse($fileDef['use'], true)) {
    echo '<div style="background:#f0f0f0;margin-top:10px;margin-bottom:10px;border-top:1px solid #E0E0E0;border-bottom:1px solid #E0E0E0;">';
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'">';
    }

    echo '<img src="'.$file->getURL().'" style="width:'. $fileDef['width'] .'px;height:'. $fileDef['height'] .'px;" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
    echo '</div>';
}
?>
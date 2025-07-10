<?php
if (!$parent->context->isPreview() && !$context->hasNotExpired()) {
    return;
}

if ($context->getColFromSort() == 'twocol') {
    $width = UNL_ENews_File_Image::FULL_AD_WIDTH;
    $height = UNL_ENews_File_Image::FULL_AD_HEIGHT;
} else {
    $width = UNL_ENews_File_Image::HALF_AD_WIDTH;
    $height = UNL_ENews_File_Image::HALF_AD_HEIGHT;
}

if ($file = $context->getFileByUse('originalimage')) {
    $description = $file->name;
    if (!empty($file->description)) {
        $description = $file->description;
    }

    echo '<div style="background:#f0f0f0;padding:10px;margin-top:10px;margin-bottom:10px;">';
    if (!empty($context->website)) {
        echo '<a href="'.$context->website.'" style="border:0;">';
    }

    echo '<img alt="' . $description .'" src="'.$file->getURL().'" style="border:0; width:'. $width .'px;height:'. $height .'px;" border="0" />';

    if (!empty($context->website)) {
        echo '</a>';
    }
    echo '</div>';
}
?>
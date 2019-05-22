<form id="enewsImage" name="enewsImage" class="dcf-form enews energetic" action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="_type" value="file" />
<?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
<input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
<input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">

<fieldset>
    <ol style="padding:0;margin-top:0;">
        <li>
            <label class="dcf-label" for="image">Image<span class="helper">To be displayed with your announcement</span></label>
            <input class="dcf-input-file" id="image" name="image" type="file" />
        </li>
        <li>
            <label class="dcf-label" for="file_description" id="img_description_label">Image Description
            <?php
            $disabled = 'disabled="disabled"';
            if (!empty($originalImage)) {
                $disabled = 'class="dcf-required"';
                echo '<span class="dcf-required">*</span>';
            }
            ?>
            <span class="helper">To be used as a caption on the web view</span></label>
            <input class="dcf-input-text required" id="file_description" name="file_description" <?php echo $disabled; ?> type="text" value="<?php echo getValue($originalImage, 'description'); ?>" />
        </li>
    </ol>
    
    <div id="upload_area">
    <?php
    $ratio       = '4:3';
    $ratio_class = 'r43';
    if ($id && $originalImage) :
        if ($thumbnail = UNL_ENews_Story::getByID($id)->getFileByUse('thumbnail')) {
            list($width, $height) = $thumbnail->getSize();
            if ($width/$height == 0.75) {
                $ratio       = '3:4';
                $ratio_class = 'r34';
            }
        }
    ?>
        <img onload="require([ENEWS_HOME+'js/submission.js'],function(submission){if(submission.announcementType != 'ad')submission.loadImageCrop('<?php echo $ratio; ?>');})" src="<?php echo $originalImage->getURL(); ?>" alt="Image to accompany submission" />
    <?php else : ?>
        <div>Image preview</div>
    <?php endif; ?>
    </div>
    <ul id="imageControls">
        <li id="cropMessage">Click and drag on the image above to select a thumbnail</li>
        <li id="cropRatio" class="<?php echo $ratio_class; ?>">Change Crop Ratio</li>
    </ul>
</fieldset>
</form>

<form id="enewsImage" name="enewsImage" class="enews energetic" action="#" method="post" enctype="multipart/form-data" style="display:none;">
<input type="hidden" name="_type" value="file" />

<fieldset>
    <ol style="padding:0;margin-top:0;">
        <li>
            <label for="image">Image<span class="helper">To be displayed with your announcement</span></label>
            <input id="image" name="image" type="file" />
        </li>
        <li>
            <label for="file_description">Image Description<span class="helper">To be used as a caption on the web view</span></label>
            <?php
            $disabled = 'disabled="disabled"';
            if (isset($originalImage)) {
                $disabled = '';
            }
            ?>
            <input id="file_description" name="file_description" <?php echo $disabled; ?> type="text" value="<?php echo getValue($originalImage, 'description'); ?>" />
        </li>
    </ol>
    
    <div id="upload_area">
    <?php
    if ($id && $originalImage) :
    
    $ratio = '4:3';
    if ($thumbnail) {
        list($width, $height) = $thumbnail->getSize();
        if ($width/$height == 0.75) {
            $ratio = '3:4';
        }
    }
    
    ?>
        <img onload="if(submission.announcementType != 'ad')submission.loadImageCrop('<?php echo $ratio; ?>');" src="<?php echo UNL_ENews_Controller::getURL().'?view=file&id='.$originalImage->id; ?>" alt="Image to accompany submission" />
    <?php else : ?>
        <div>Image preview</div>
    <?php endif; ?>
    </div>
    <ul id="imageControls">
        <li id="cropMessage">Click and drag on the image above to select a thumbnail</li>
        <li id="cropRatio">Change Crop Ratio</li>
    </ul>
</fieldset>
</form>
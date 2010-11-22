<form id="enewsImage" name="enewsImage" class="enews energetic" action="#" method="post" enctype="multipart/form-data" style="display:none;">
<input type="hidden" name="_type" value="file" />

<?php //Story id that gets attached when the story is submitted ?>
<input type="hidden" id="storyid" name="storyid" value="" />

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
            if (isset($original_image)) {
                $disabled = '';
            }
            ?>
            <input id="file_description" name="originalimage_description" <?php echo $disabled; ?> type="text" value="<?php echo getValue($original_image, 'description'); ?>" />
        </li>
    </ol>
    
    <div id="upload_area">
    <?php if ($id) : ?>
        <?php if ($original_image) : ?>
            <img src="<?php echo UNL_ENews_Controller::getURL().'?view=file&id='.$original_image->id; ?>" alt="Image to accompany story submission" />
            <script type="text/javascript">submission.loadImageCrop();</script>
        <?php endif; ?>
    <?php else : ?>
        <div>Image preview</div>
    <?php endif; ?>
    </div>
    <div id="cropMessage">Click and drag on the image above to select a thumbnail</div>
</fieldset>
</form>
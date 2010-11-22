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
    <?php if ($id) : ?>
        <?php if ($originalImage) : ?>
            <img src="<?php echo UNL_ENews_Controller::getURL().'?view=file&id='.$originalImage->id; ?>" alt="Image to accompany story submission" />
            <script type="text/javascript">
            submission.loadImageCrop();
            WDN.jQuery(document).ready(function($) {
                $('#upload_area img').bind('click', function() {
                    var imgString = '<img src="'+ENEWS_HOME+'?view=file&id='+$('#enewsSubmission #fileID').val()+'" alt="Uploaded Image" onload="submission.loadImageCrop();" />';
                    $('#sampleLayoutImage').html(imgString);
                });
            });
            </script>
        <?php endif; ?>
    <?php else : ?>
        <div>Image preview</div>
    <?php endif; ?>
    </div>
    <div id="cropMessage">Click and drag on the image above to select a thumbnail</div>
</fieldset>
</form>
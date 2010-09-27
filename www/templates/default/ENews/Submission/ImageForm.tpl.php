<?php
$image = null;
if ($id = getValue($context, "id")) {
    $image = UNL_ENews_Story::getByID($id)->getFileByUse('originalimage');
}
?>
<form id="enewsImage" name="enewsImage" class="enews energetic" action="?view=submit" method="post" enctype="multipart/form-data" style="display:none;">
<input type="hidden" name="_type" value="file" />

<?php //Story id that gets attached when the story is submitted above ?>
<input type="hidden" id="storyid" name="storyid" value="" /> 

<fieldset>
            <ol style="padding:0;margin-top:0;"><li>
            <label for="image">Image<span class="helper">To be displayed with your announcement</span></label>
            <input id="image" name="image" type="file" />
            </li></ol>
            
            <div id="upload_area">
            <?php if ($id) { ?>
                    <?php if ($image) { ?>
                            <img src="<?php echo UNL_ENews_Controller::getURL().'?view=file&id='.$image->id; ?>" alt="Image to accompany story submission" />
                            <span><script type="text/javascript">document.write(ajaxUpload.message);</script></span>
                            <script type="text/javascript">WDN.loadJS("<?php echo UNL_ENews_Controller::getURL();?>/js/jquery.imgareaselect.pack.js",function(){submission.setImageCrop();},true,true);</script>
                    <?php } ?>
            <?php } else { ?>
                    <div>Image preview</div>
            <?php }  ?>
            </div>
</fieldset>
</form>

<form id="enewsImageDescription" action="?view=submit" method="post" class="enews energetic" style="display:none;">
    <input type="hidden" name="_type" value="file" />
    <input type="hidden" name="id" value="<?php echo getValue($image, 'id'); ?>" />
    <fieldset>
        <ol style="padding:0;margin-top:0;"><li>
            <label for="file_description">Image Caption<span class="helper">The Description of the image</span></label>
            <?php
            $disabled = 'disabled="disabled"';
            if (isset($image)) {
                $disabled = '';
            }
            ?>
            <input id="file_description" name="description" <?php echo $disabled; ?> type="text" value="<?php echo getValue($image, 'description'); ?>" />
            </li>
        </ol>
    </fieldset>
</form>
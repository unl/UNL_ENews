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
                    <?php if ($original_image) { ?>
                            <img src="<?php echo UNL_ENews_Controller::getURL().'?view=file&id='.$original_image->id; ?>" alt="Image to accompany story submission" />
                            <span><script type="text/javascript">document.write(ajaxUpload.message);</script></span>
                            <script type="text/javascript">WDN.loadJS("<?php echo UNL_ENews_Controller::getURL();?>/js/jquery.imgareaselect.pack.js",function(){submission.setImageCrop();},true,true);</script>
                    <?php } ?>
            <?php } else { ?>
                    <div>Image preview</div>
            <?php }  ?>
            </div>
</fieldset>
</form>
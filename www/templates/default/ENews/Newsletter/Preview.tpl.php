
<script type="text/javascript">
WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
</script>
<form id="enews" class="energetic" method="post" action="?view=newsletter&amp;newsletter_id=<?php echo $context->newsletter->id; ?>">
	<fieldset style="background:url('images/process_step_fade.jpg') repeat-x top #fbfaf6;">
	<legend>Your Newsletter</legend>
	<ol>
		<li>
			<input type="hidden" name="_type" value="newsletter" />
    		<input type="hidden" name="id" id="id" value="<?php echo $context->newsletter->id; ?>" />
    		<label for="emailSubject">Email Subject<span class="required">*</span></label>
    		<input name="subject" type="text" value="<?php echo $context->newsletter->subject; ?>" id="emailSubject" />
    	</li>
	</ol>
    
    </fieldset>
    <p class="submit"><input type="submit" name="submit" value="Save" /></p>
    
</form>
    <div class="col left">
    	<div class="zenbox energetic" id="drag_story_list" style="margin-top:200px;"> 
		    <h3>Available News</h3>
			<?php foreach ($context->available_stories as $story): ?>
		    	<div id="drag_story_<?php echo $story->id; ?>" class="dragItem">
		            <?php echo $savvy->render($story, 'ENews/Newsletter/Story.tpl.php'); ?>
		        </div>
			<?php endforeach; ?>
		</div>
    </div>
    <div class="three_col right">
	    <?php echo $savvy->render($context->newsletter); ?>
    </div>

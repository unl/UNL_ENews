<script type="text/javascript" src="js/functions.js"></script>
<form method="post" action="?view=newsletter&amp;newsletter_id=<?php echo $context->newsletter->id; ?>">
    <input type="hidden" name="_type" value="newsletter" />
    <input type="hidden" name="id" id="id" value="<?php echo $context->newsletter->id; ?>" />
    Email Subject: <input name="subject" size="100" value="<?php echo $context->newsletter->subject; ?>" />
    <input type="submit" value="Save" />
</form>
    <div class="col left">
    	<div class="zenbox energetic" id="drag_story_list"> 
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

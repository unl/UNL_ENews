<form method="post" action="?view=newsletter&amp;id=<?php echo $context->newsletter->id; ?>">
    <input name="_type" value="newsletter" type="hidden" />
    Email Subject: <input name="subject" size="100" value="<?php echo $context->newsletter->subject; ?>" />
    <div class="col left zenbox energetic">
    <h3>Available News</h3>
	<div id="news1" class="dragItem">
	<?php foreach ($context->available_stories as $story): ?>
    	<h4><?php echo $story->title; ?></h4>
    	<p><?php echo $story->description; ?></p>
	<?php endforeach; ?>
	</div>
    
    
    
    
    </div>
    <div class="three_col right">
    <?php echo $savvy->render($context->newsletter); ?>
    </div>
    <input type="submit" value="Save" />
</form>

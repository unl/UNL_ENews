<form method="post" action="?view=newsletter&amp;id=<?php echo $context->newsletter->id; ?>">
    <input name="_type" value="newsletter" type="hidden" />
    Email Subject: <input name="subject" size="100" value="<?php echo $context->newsletter->subject; ?>" />
    <div class="col left zenbox energetic">
    <h3>Available News</h3>
	<div id="news1" class="dragItem">
	<h4>News Heading</h4>
	<p>News article, slightly shortened...</p>
	</div>
    
    
    
    
    </div>
    <div class="three_col right">
    <?php echo $savvy->render($context->newsletter); ?>
    </div>
    <input type="submit" value="Save" />
</form>

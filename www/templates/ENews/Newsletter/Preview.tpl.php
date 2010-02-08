<script type="text/javascript" src="js/functions.js"></script>
<form method="post" action="?view=newsletter&amp;newsletter_id=<?php echo $context->newsletter->id; ?>">
    <input type="hidden" name="_type" value="newsletter" />
    <input type="hidden" name="id" id="id" value="<?php echo $context->newsletter->id; ?>" />
    Email Subject: <input name="subject" size="100" value="<?php echo $context->newsletter->subject; ?>" />
    <input type="submit" value="Save" />
</form>
    <div class="col left zenbox energetic" id="drag_story_list">
    <h3>Available News</h3>
	<?php foreach ($context->available_stories as $story): ?>
    	<div id="drag_story_<?php echo $story->id; ?>" class="dragItem">
            <h4><?php echo $story->title; ?></h4>
            <p><?php echo $story->description; ?></p>
	        <form method="post" action="?view=newsletter&amp;newsletter_id=<?php echo $context->newsletter->id; ?>">
            <input type="hidden" name="_type" value="addstory" />
            <input type="hidden" name="story_id" value="<?php echo $story->id; ?>" />
            <input type="hidden" name="sort_order" value="0" />
            <input type="hidden" name="intro" value="" />
            <input type="submit" value="add story" />
        </form>
        </div>
	<?php endforeach; ?>
    
    
    
    
    </div>
    <div class="three_col right">
    <?php echo $savvy->render($context->newsletter); ?>
    </div>

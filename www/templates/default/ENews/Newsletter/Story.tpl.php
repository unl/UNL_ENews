<?php
$newsletter_url = $context->newsletter->getURL();
?>
<div class="three_col left">
    <?php echo $savvy->render($context->story); ?>
</div>
<div class="col right">
    <div class="sidebar">
    	<div class="inner_sidebar">
    		<h3>
	            <?php echo $context->newsroom->name; ?>
	            <span class="date">
	            <?php echo date('D. M d, Y', strtotime($context->newsletter->release_date)) ?>
	            </span>
	        </h3>
	        <ul>
		    <?php 
		    foreach ($context->newsletter->getStories() as $key=>$story) {
		        echo '<li><a href="'.$newsletter_url.'/'.$story->id.'">'.$story->title.'</a></li>';
		    }
		    ?>
		    </ul>
	    </div>
	</div>
</div>

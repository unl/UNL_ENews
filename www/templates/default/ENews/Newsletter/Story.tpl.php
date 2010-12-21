<?php
$newsletter_url = $context->newsletter->getURL();
?>
<div class="three_col left">
    <?php echo $savvy->render($context->story); ?>
</div>
<div class="col right">
    <div class="sidebar top">
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
	<div class="sidebar bottom">
	    <div class="inner_sidebar">
	        <div class="subscribe">
		        <h3><?php echo $context->newsroom->name; ?><span>Subscribe Today!</span></h3>
		        <?php foreach ($context->newsroom->getEmails() as $email) :
		        if ($email->optout) :
			        ?>
			        <form method="post" action="http://listserv.unl.edu/signup-anon/" id="subscribe">
			        	<label for="address">Email</label>
			        	<input type="text" id="address" name="ADDRESS" />
			        	<input type="hidden" id="address" value="<?php echo $context->newsroom->getURL();?>" name="SUCCESS_URL" />
			        	<input type="hidden" value="BOTH" name="LOCKTYPE" />
			        	<input type="hidden" name="LISTNAME" value="<?php echo substr($email->email, 0, strpos($email->email, '@')); ?>" />
			        	<input type="submit" value="Subscribe" name="submit" />
			        </form>
		        <?php
		        	endif;
		        endforeach; ?>
	        </div>
	    </div>
    </div>
</div>
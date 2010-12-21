<?php
UNL_ENews_Controller::setReplacementData('pagetitle', $context->subject);
?>
<div class="three_col left">
<h3 class="sec_main">
    <?php echo $context->subject; ?>
</h3>
<div id="newsletterWeb">
    <?php echo $savvy->render($context->getStories(), 'ENews/Newsletter/StoriesWeb.tpl.php'); ?>

    <div style="clear:both;display:block;text-align:center;font-size:.8em;border-top:1px solid #E0E0E0;margin-top:5px;padding-top:5px">
        Originally published <?php echo date('l F j, Y', strtotime($context->release_date)); ?>
        -
        <a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit an Item</a>
    </div>
</div>
</div>
<div class="col right">
    <div class="sidebar top">
    	<div class="inner_sidebar">
    		<div class="archives">
		        <h3>
		            <?php echo $context->newsroom->name; ?>
		            <span>Recent Newsletters</span>
		        </h3>
		        <ul>
		        <?php
		        foreach (UNL_ENews_NewsletterList::getRecent($context->newsroom->id, 5) as $newsletter) {
		            echo "<li> <a href=".$newsletter->getURL().">".date('D. M d, Y', strtotime($newsletter->release_date))."</a> </li>";
		        }
		        ?>
		        </ul>
		        <a href="<?php echo $context->newsroom->getURL();?>/archive">Full archives</a>
	        </div>
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
			        	<input type="text" id="address" name="ADDRESS" value="" />
			        	<input type="hidden" id="address" value="<?php echo $context->newsroom->getURL();?>" name="SUCCESS_URL" />
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
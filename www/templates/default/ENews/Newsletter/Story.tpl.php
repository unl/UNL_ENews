<?php
UNL_ENews_Controller::$sitetitle = $context->newsroom->name;
$newsletter_url = $context->newsletter->getURL();
/* @var $context UNL_ENews_Newsletter_Story */
?>
<div class="three_col left">
    <?php echo $savvy->render($context->story); ?>
</div>
<div class="col right">
    <div class="sidebar top">
      <div class="inner_sidebar">
        <h3>
              <a href="<?php echo $context->getURL(); ?>" title="Go to the newsletter index page"><?php echo $context->newsroom->name; ?>
              <span class="date">
              <?php echo UNL_ENews_Controller::formatDate($context->newsletter->release_date); ?>
              </span>
              </a>
		</h3>
		<ul>
        <?php 
        foreach ($context->newsletter->getStories() as $key => $story) {
            if ($story->presentation->type != 'ad') {
                echo '<li><a href="'.$newsletter_url.'/'.$story->id.'">'.$story->title.'</a></li>';
            }
        }
        ?>
        </ul>
      </div>
      <div class="newsletters">
		<?php
		$newsletters = $context->story->getNewsletters();
		// No sense in showing just the current newsletter
		if (count($newsletters) > 1) {
		    echo '
		    <h3>Other newsletters including this story</h3>
		    <ul>';
    		foreach ($newsletters as $newsletter) {
    		    /* @var $newsletter UNL_ENews_Newsletter */
    		    echo '<li><a href="'.$newsletter->getURL().'">'.$newsletter->subject.'</a></li>';
    		}
    		echo '</ul>';
		}
		?>
      </div>
  </div>
  <div class="sidebar bottom">
      <div class="inner_sidebar">
          <?php echo $savvy->render($context->newsroom, 'ENews/Newsroom/SubscribeForm.tpl.php'); ?>
      </div>
    </div>
</div>
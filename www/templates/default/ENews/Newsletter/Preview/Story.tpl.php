<?php
/* @var $savvy Savvy */
/* @var $context UNL_ENews_Newsletter_Preview_Story */
?>
<div class="story-content">
<?php echo $savvy->render($context->story, $context->story->getRenderer('templates/email')); ?>
<div class="clear"></div>
</div>
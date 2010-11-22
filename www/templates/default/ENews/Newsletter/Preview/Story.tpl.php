<?php
/* @var $savvy Savvy */
/* @var $context UNL_ENews_Newsletter_Preview_Story */
?>
<div class="story-content">
<?php echo $savvy->render($context->story, 'templates/email/ENews/Newsletter/Story/Presentation/' . $context->story->getPresentation()->template); ?>
<div class="clear"></div>
</div>
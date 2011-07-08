<?php /* @var $context UNL_ENews_Newsletter_StoryColumn */ ?>
<div id="<?php echo $context->getHtmlAttribute('id') ?>" class="<?php echo $context->getHtmlAttribute('class') ?>">
<?php foreach ($context->getStoriesIterator() as $story): ?>
    <article class="story" id="story_<?php echo $story->story_id; ?>">
    <?php if ($context->isPreview()): ?>
        <div class="story-content">
            <?php echo $savvy->render($story, $story->getRenderer('templates/email')); ?>
            <div class="clear"></div>
        </div>
        <?php echo $savvy->render($story, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>
    <?php elseif ($context->isForWeb()): ?>
        <?php echo $savvy->render($story, $story->getRenderer()); ?>
        <div class="clear"></div>
    <?php else: ?>
        <?php echo $savvy->render($story, $story->getRenderer()); ?>
        <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
    <?php endif; ?>
    </article>
<?php endforeach; ?>
</div>
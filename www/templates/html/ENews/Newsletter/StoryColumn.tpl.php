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
        <?php
        echo $savvy->render($story, $story->getRenderer());
        $spacer_width  = $story->getWidthFromSortOrder();
        $spacer_url    = UNL_ENews_Controller::getURL() . 'templates/email/ENews/images/'.$spacer_width.'_10.gif';
        $spacer_height = 10;
        ?>
        <img class="spacer" src="<?php echo $spacer_url; ?>" width="<?php echo $spacer_width; ?>" height="10" />
    <?php endif; ?>
    </article>
<?php endforeach; ?>
</div>
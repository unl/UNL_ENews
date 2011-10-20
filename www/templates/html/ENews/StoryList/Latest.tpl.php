<?php
if ($context->options['format'] == 'partial') {
    echo $savvy->render($context, 'ENews/StoryList.tpl.php');
    return;
}
?>
<div class="three_col left">
    <h3 class="sec_main">
        <?php echo $context->newsroom->name;?><a class="rsslink right" href="<?php echo $context->newsroom->getURL(); ?>/latest?format=rss">RSS</a>
    </h3>
    <?php echo $savvy->render($context, 'ENews/StoryList.tpl.php'); ?>
</div>
<div class="col right">
    <a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit News</a>
</div>
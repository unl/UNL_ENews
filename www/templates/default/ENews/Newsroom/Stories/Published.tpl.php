<?php
if ($context->options['format'] == 'partial') {
    echo $savvy->render($context, 'ENews/StoryList.tpl.php');
    return;
}
?>
<div class="three_col left">
    <h3 class="sec_main">
        <?php echo $context->newsroom->name;?><a class="rsslink right" href="<?php echo $context->newsroom->getURL(); ?>/stories?format=rss">RSS</a>
    </h3>
    <?php echo $savvy->render($context, 'ENews/StoryList.tpl.php'); ?>
    <?php
    if (isset($context->options['limit'])
        && count($context) > $context->options['limit']) {
        $pager = new stdClass();
        $pager->total  = count($context);
        $pager->limit  = $context->options['limit'];
        $pager->offset = $context->options['offset'];
        $pager->url    = $context->newsroom->getURL().'/stories';
        echo $savvy->render($pager, 'ENews/PaginationLinks.tpl.php');
    }
    ?>
</div>
<div class="col right">
    <a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit News</a>
</div>

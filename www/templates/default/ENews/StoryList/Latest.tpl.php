<div class="three_col left">
    <h3 class="sec_main">
        <?php echo $context->newsroom->name;?><a class="rsslink right" href="<?php echo $context->newsroom->getURL(); ?>/latest?format=rss">RSS</a>
    </h3>
    <ul>
        <?php foreach($context as $item) :?>
        <li style="clear:both"><?php echo $savvy->render($item); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="col right">
    <a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit News</a>
    <br />
</div>
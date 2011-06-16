<div class="three_col left">
    <h3 class="sec_main">
        <?php echo $context->newsroom->name;?><a class="rsslink right" href="<?php echo $context->newsroom->getURL(); ?>/latest?format=rss">RSS</a>
    </h3>
    <div class="stories">
        <?php
        foreach($context as $item) {
            echo $savvy->render($item, 'ENews/Newsletter/Story/Summary.tpl.php');
        }
        ?>
    </div>
</div>
<div class="col right">
    <a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit News</a>
</div>
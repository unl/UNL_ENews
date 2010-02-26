<div class="three_col left">
    <h3><?php echo $context->newsroom->name;?><a class="rsslink right" href="?view=latest&amp;newsroom=<?php echo $context->newsroom->id;?>&amp;format=rss">RSS</a></h3>
    <ul>
        <?php foreach($context as $item) :?>
        <li style="clear:both"><?php echo $savvy->render($item, 'ENews/Newsletter/Story.tpl.php'); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="col right">
    <a href="?view=submit&amp;newsroom=<?php echo $context->newsroom->id;?>">Submit News</a>
    <br />
    
</div>
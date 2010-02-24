<div class="three_col left">
    <h1>Latest from <?php echo $context->newsroom->name;?></h1>
    <ul>
        <?php foreach($context as $item) :?>
        <li style="clear:both"><?php echo $savvy->render($item, 'ENews/Newsletter/Story.tpl.php'); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="col right">
    <a href="?view=submit">Submit News</a>
    <br />
    <a href="?view=latest&amp;newsroom=<?php echo $context->newsroom->id;?>&amp;format=rss">RSS</a>
</div>
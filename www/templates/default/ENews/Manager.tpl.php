<h3 class="sec_main">
    <?php echo $context->newsroom->name;?>
    (<a href="?view=latest&amp;newsroom=<?php echo $context->newsroom->id;?>">Live View</a>, 
     <a class="rsslink" href="?view=latest&amp;newsroom=<?php echo $context->newsroom->id;?>&amp;format=rss">RSS</a>
    )
</h3>

<?php
if ($context->actionable) {
    echo $savvy->render($context->actionable[0]);
}
?>

<ul class="wdn_tabs disableSwitching">
    <?php foreach (array('pending', 'approved', 'archived') as $type):
    $class = '';
    if ($context->options['status'] == $type) {
        $class = ' class="selected"';
    }
    ?>
    <li <?php echo $class; ?>><a href="?view=manager&amp;newsroom=<?php echo $context->newsroom->id;?>&amp;status=<?php echo $type; ?>"><?php echo ucfirst($type); ?>
        <sup><?php echo count($context->newsroom->getStories($type)); ?></sup></a></li>
    <?php endforeach; ?>
</ul>
<?php
if ($context->actionable) { 
    echo $savvy->render($context->actionable[1]);
} else {
    echo 'No gnews is good gnews with Gary Gnu.';
}
?>
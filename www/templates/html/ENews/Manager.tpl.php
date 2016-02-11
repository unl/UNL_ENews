<h3 class="clear-top wdn-brand">
    <?php echo $context->newsroom->name;?>
    (<a href="<?php echo $context->newsroom->getURL();?>">Live View</a>, 
     <a class="rsslink" href="<?php echo $context->newsroom->getURL();?>/latest?format=rss">RSS</a>
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
    <li <?php echo $class; ?>><a href="<?php echo $context->newsroom->getURL();?>/manage?status=<?php echo $type; ?>"><?php echo ucfirst($type); ?>
        <sup><?php echo count($context->newsroom->getStories($type)); ?></sup></a></li>
    <?php endforeach; ?>
</ul>
<?php
if ($context->actionable
    && isset($context->actionable[1])) { 
    echo $savvy->render($context->actionable[1]);
} else {
    echo '<div class="four_col">No gnews is good gnews with Gary Gnu.</div>';
}
?>

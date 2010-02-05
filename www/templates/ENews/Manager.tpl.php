<ul class="wdn_tabs disableSwitching">
    <?php foreach (array('pending', 'approved', 'archived') as $type):
    $class = '';
    if ($context->options['status'] == $type) {
        $class = ' class="selected"';
    }
    ?>
    <li <?php echo $class; ?>><a href="?view=manager&amp;status=<?php echo $type; ?>"><?php echo ucfirst($type); ?>
        <sup><?php echo count($context->newsroom->getStories($type)); ?></sup></a></li>
    <?php endforeach; ?>
</ul>
<?php
if ($context->actionable) { 
    echo $savvy->render($context->actionable);
} else {
    echo 'No gnews is good gnews with Gary Gnu.';
}
?>
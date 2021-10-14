<div class="dcf-grid dcf-mb-6">
    <div class="dcf-col-100% dcf-col-33%-end@md dcf-2nd@md">
        <?php
        if ($context->actionable) {
            echo $savvy->render($context->actionable[0]);
        }
        ?>
    </div>
    <div class="dcf-col-100% dcf-col-67%-start@md dcf-1st@md">
        <h3 class="wdn-brand">
            <?php echo $context->newsroom->name;?>
             <a class="rsslink" href="<?php echo $context->newsroom->getURL();?>/latest?format=rss">RSS</a>
        </h3>
    </div>
</div>

<ul class="wdn_tabs disableSwitching">
    <?php foreach (array('pending', 'approved', 'archived') as $type):
    $class = '';
    if ($context->options['status'] == $type) {
        $class = ' class="selected"';
    }
    ?>
    <li <?php echo $class; ?>><a href="<?php echo $context->newsroom->getURL();?>/manage?status=<?php echo $type; ?>"><?php echo ucfirst($type); ?>
        <small class="dcf-badge dcf-badge-pill"><?php echo count($context->newsroom->getStories($type)); ?></small></a></li>
    <?php endforeach; ?>
</ul>
<div class="dcf-tabs-panel">
<?php
if ($context->actionable
    && isset($context->actionable[1])) { 
    echo $savvy->render($context->actionable[1]);
} else {
    echo '<div class="four_col">No gnews is good gnews with Gary Gnu.</div>';
}
?>
</div>

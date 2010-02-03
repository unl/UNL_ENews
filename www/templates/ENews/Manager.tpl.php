<ul class="wdn_tabs disableSwitching">
    <?php foreach (array('pending', 'approved', 'archived') as $type):
    $class = '';
    if ($context->options['type'] == $type) {
        $class = ' class="selected"';
    }
    ?>
    <li <?php echo $class; ?>><a href="?view=manager&amp;type=<?php echo $type; ?>"><?php echo ucfirst($type);
    if ($context->options['type'] == $type
        && count($context->actionable)): ?>
        <sup><?php echo count($context->actionable); ?></sup>
    <?php endif; ?></a></li>
    <?php endforeach; ?>
</ul>
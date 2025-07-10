<?php echo $savvy->render($context->newsletter); ?>
<?php if (!$context->newsletter): ?>
    <?php
        UNL_ENews_PostRunFilter::setReplacementData('pagetitle', $context->newsroom->name);
        UNL_ENews_PostRunFilter::setReplacementData('sitetitle', $context->newsroom->name);
    ?>
    <p>This newsroom currently has no published newsletters.</p>
<?php endif; ?>

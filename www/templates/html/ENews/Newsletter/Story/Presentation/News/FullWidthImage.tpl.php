<?php if ($file = $context->getFileByUse('originalimage')): ?>

    <?php if (($context->website)): ?>
        <a href="<?php echo $context->website; ?>">
    <?php endif; ?>
    <img src="<?php echo $file->getURL(); ?>" width="100%" class="announcefullwidth" style="margin-bottom:5px;" />
    <?php if (($context->website)): ?>
        </a>
    <?php endif; ?>

    <p style="font-size:0px;line-height:1px;padding:0">
        <?php if (($context->website)): ?>
            <a href="<?php echo $context->website; ?>" style="color:#D00000;word-wrap: break-word;">
        <?php endif; ?>
        <?php echo $file->description; ?>
        <?php if (($context->website)): ?>
            </a>
        <?php endif; ?>
    </p>
<?php endif; ?>

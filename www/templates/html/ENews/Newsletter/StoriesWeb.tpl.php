<?php foreach (array('news', 'ads') as $area): ?>
	<?php
    $stories = $context->getStoriesByColumn($area);
    $displayColumn = !empty($stories[1]);
    ?>
    <?php if ($displayColumn): ?>
    <?php echo $savvy->render($context->getStoryColumn($stories[1], array(
        'area' => $area,
        'offset' => 1,
        'web' => true
    ))); ?>
    <?php endif; ?>
    <?php
    if ($area == 'news') {
        $displayColumn = !empty($stories[2]) || !empty($stories[0]);
    } else {
        $displayColumn = !$displayColumn && (!empty($stories[2]) || !empty($stories[0]));
    }
    ?>
    <?php if ($displayColumn): ?>
        <div class="wdn-grid-set">
            <div class="bp768-wdn-col-one-half">
                <?php echo $savvy->render($context->getStoryColumn($stories[2], array(
                    'area' => $area,
                    'offset' => 2,
                    'web' => true
                ))); ?>
            </div>
            <div class="bp768-wdn-col-one-half">
                <?php echo $savvy->render($context->getStoryColumn($stories[0], array(
                    'area' => $area,
                    'offset' => 0,
                    'web' => true
                ))); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="clear"></div>
<?php endforeach; ?>

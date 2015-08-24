<?php $isPreview = $context->getIsPreview(); ?>
<?php foreach (array('news', 'ads') as $area): ?>
    <?php
    $stories = $context->getStoriesByColumn($area);
    $displayColumn = $isPreview || !empty($stories[1]);
    ?>
    <?php if ($displayColumn): ?>
    <tr>
        <td colspan="3">
            <?php echo $savvy->render($context->getStoryColumn($stories[1], array(
                'area' => $area,
                'offset' => 1,
                'preview' => $isPreview
            ))); ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php
    if ($area == 'news') {
        $displayColumn = $isPreview || (!empty($stories[2]) || !empty($stories[0]));
    } else {
        $displayColumn = $isPreview || (!$displayColumn && (!empty($stories[2]) || !empty($stories[0])));
    }
    ?>
    <?php if ($displayColumn): ?>
    <tr>
         <td>
             <?php echo $savvy->render($context->getStoryColumn($stories[2], array(
                'area' => $area,
                'offset' => 2,
                'preview' => $isPreview
            ))); ?>
        </td>
        <td width="10">&nbsp;</td>
        <td>
            <?php echo $savvy->render($context->getStoryColumn($stories[0], array(
                'area' => $area,
                'offset' => 0,
                'preview' => $isPreview
            ))); ?>
        </td>
    </tr>
    <?php endif; ?>
<?php endforeach; ?>

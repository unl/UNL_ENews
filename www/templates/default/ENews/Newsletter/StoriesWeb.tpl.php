<?php $areas = $context->getStoriesByColumn(); ?>

<?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
    'id' => 'newsColumnIntro',
    'class' => 'newsColumn',
    'stories' => $areas['news'][1],
    'web' => true
))); ?>

<div style="width:340px;padding:0 10px 0 0;float:left;">
    <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
        'id' => 'newsColumn1',
        'class' => 'newsColumn',
        'stories' => $areas['news'][2],
    	'web' => true
    ))); ?>
</div>
<div style="width:340px;padding:0 0 0 10px;float:right;">
    <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
        'id' => 'newsColumn2',
        'class' => 'newsColumn',
        'stories' => $areas['news'][0],
    	'web' => true
    ))); ?>
</div>

<div class="clear"></div>
<?php if (!empty($areas['ads'][1])): ?>
    <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
        'id' => 'adAreaIntro',
        'class' => 'adArea',
        'stories' => $areas['ads'][1],
        'filter' => 1, // Forces to only render 1 ad
    	'web' => true
    ))); ?>
<?php elseif (!empty($areas['ads'][2]) || !empty($areas['ads'][2])): ?>
<div style="width:340px;padding:0 10px 0 0;float:left;">
	<?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
        'id' => 'adArea1',
        'class' => 'adArea',
        'stories' => $areas['ads'][2],
	    'filter' => 1, // Forces to only render 1 ad
    	'web' => true
    ))); ?>
</div>
<div style="width:340px;padding:0 0 0 10px;float:right;">
    <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
        'id' => 'adArea2',
        'class' => 'adArea',
        'stories' => $areas['ads'][0],
	    'filter' => 1, // Forces to only render 1 ad
    	'web' => true
    ))); ?>
</div>
<?php endif; ?>
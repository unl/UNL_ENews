<?php
$columns = array();
$adAreas = array();
foreach ($context as $key=>$story) {
    $areaPtr =& $columns;
    if ($story->getPresentation()->type == 'ad') {
        $areaPtr =& $adAreas;
    }

    $areaPtr[$story->sort_order % 3][] = $story;
}
?>
<div id="newsColumnIntro" class="newsColumn">
<?php if (isset($columns[1])): ?>
<?php foreach ($columns[1] as $story): ?>
    <div class="story" id="story_<?php echo $story->story_id; ?>">
        <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
        <div class="clear"></div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<div style="width:340px;padding:0 10px 0 0;float:left;">
    <div id="newsColumn1" class="newsColumn">
    <?php if (isset($columns[2])): ?>
    <?php foreach ($columns[2] as $story): ?>
        <div class="story" id="story_<?php echo $story->story_id; ?>">
            <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
            <div class="clear"></div>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>
<div style="width:340px;padding:0 0 0 10px;float:right;">
    <div id="newsColumn2" class="newsColumn">
    <?php if (isset($columns[0])): ?>
    <?php foreach ($columns[0] as $story): ?>
        <div class="story" id="story_<?php echo $story->story_id; ?>">
            <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
            <div class="clear"></div>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>

<div class="clear"></div>
<?php if (!empty($adAreas[1])): ?>
<div id="adAreaIntro" class="adArea">
	<div id="adAreaIntro" class="adArea">
	<?php $story = $adAreas[1][0]; // Intentional single story ?>
		<div class="story" id="story_<?php echo $story->story_id; ?>">
	        <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
        </div>
	</div>
</div>
<?php elseif (!empty($adAreas[2]) || !empty($adAreas[0])): ?>
<div style="width:340px;padding:0 10px 0 0;float:left;">
	<div id="adArea1" class="adArea">
	<?php if (!empty($adAreas[2])): ?>
	<?php $story = $adAreas[2][0]; // Intentional single story ?>
		<div class="story" id="story_<?php echo $story->story_id; ?>">
            <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
        </div>
    <?php endif; ?>
	</div>
</div>
<div style="width:340px;padding:0 0 0 10px;float:right;">
	<div id="adArea2" class="adArea">
	<?php if (!empty($adAreas[0])): ?>
	<?php $story = $adAreas[0][0]; // Intentional single story ?>
		<div class="story" id="story_<?php echo $story->story_id; ?>">
            <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
        </div>
	<?php endif; ?>
	</div>
</div>
<?php endif; ?>
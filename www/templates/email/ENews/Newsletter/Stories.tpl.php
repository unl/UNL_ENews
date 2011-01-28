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
<tr>
    <td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
        <!-- This is the main content -->
        <div id="newsColumnIntro" class="newsColumn">
        <?php foreach ($columns[1] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
            </div>
        <?php endforeach; ?>
        </div>
    </td>
</tr>
<tr id="newsStories">
    <td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
        <div id="newsColumn1" class="newsColumn">
        <?php
        if (isset($columns[2])) :
            foreach ($columns[2] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
            </div>
            <?php
            endforeach;
        endif; ?>
        </div>
    </td>
    <td width="10">&nbsp;</td>
    <td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
        <div id="newsColumn2" class="newsColumn">
        <?php
        if (isset($columns[0])) :
            foreach ($columns[0] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
            </div>
            <?php
            endforeach;
        endif; ?>
        </div>
    </td>
</tr>
<?php if (!empty($adAreas[1])): ?>
<tr>
	<td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
		<div id="adAreaIntro" class="adArea">
		<?php $story = $adAreas[1][0]; // Intentional single story ?>
			<div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
		        <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
            </div>
		</div>
	</td>
</tr>
<?php elseif (!empty($adAreas[2]) || !empty($adAreas[0])): ?>
<tr>
	<td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
		<div id="adArea1" class="adArea">
		<?php if (!empty($adAreas[2])): ?>
		<?php $story = $adAreas[2][0]; // Intentional single story ?>
			<div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
            </div>
        <?php endif; ?>
		</div>
	</td>
	<td width="10">&nbsp;</td>
	<td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
		<div id="adArea2" class="adArea">
		<?php if (!empty($adAreas[0])): ?>
		<?php $story = $adAreas[0][0]; // Intentional single story ?>
			<div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
            </div>
		<?php endif; ?>
		</div>
	</td>
</tr>
<?php endif; ?>
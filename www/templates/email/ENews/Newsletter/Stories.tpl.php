<?php
$columns = array();
foreach ($context as $key=>$story) {
    $columns[$story->sort_order % 3][] = $story;
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
     <td valign="top" width="273">
        <div id="newsColumn1" class="newsColumn">
        <?php foreach ($columns[2] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <?php echo $savvy->render($story, 'ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
            </div>
        <?php endforeach; ?>
        </div>
    </td>
    <td width="10">&nbsp;</td>
    <td valign="top" width="273">
        <div id="newsColumn2" class="newsColumn">
        <?php foreach ($columns[0] as $story): ?>
            <div class="story" id="story_<?php echo $story->story_id; ?>" valign="top">
                <?php echo $savvy->render($story, 'templates/email/ENews/Newsletter/Story/Presentation/'.$story->getPresentation()->template); ?>
                <img class="spacer" src="http://www.unl.edu/wdn/templates_3.0/images/email/gif.gif" width="100%" height="10" />
            </div>
        <?php endforeach; ?>
        </div>
    </td>
</tr>
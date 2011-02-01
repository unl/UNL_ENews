<?php
$areas = $context->getStoriesByColumn();
$isPreview = $context->getIsPreview();
?>
<tr>
    <td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
        <!-- This is the main content -->
        <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
            'id' => 'newsColumnIntro',
            'class' => 'newsColumn',
            'stories' => $areas['news'][1],
            'preview' => $isPreview
        ))); ?>
    </td>
</tr>
<tr id="newsStories">
     <td valign="top" width="273">
         <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
            'id' => 'newsColumn1',
            'class' => 'newsColumn',
            'stories' => $areas['news'][2],
            'preview' => $isPreview
        ))); ?>
    </td>
    <td width="10">&nbsp;</td>
    <td valign="top" width="273">
        <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
            'id' => 'newsColumn2',
            'class' => 'newsColumn',
            'stories' => $areas['news'][0],
            'preview' => $isPreview
        ))); ?>
    </td>
</tr>

<?php $showAdIntro = $isPreview || !empty($areas['ads'][1]); ?>
<?php if ($showAdIntro): ?>
<tr>
    <td colspan="3" style="color:#494949; font-size: 12px; line-height: 140%; font-family: 'Lucida Grande',Verdana,Arial;">
        <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
            'id' => 'adAreaIntro',
            'class' => 'adArea',
            'stories' => $areas['ads'][1],
            'preview' => $isPreview,
            'filter' => ($isPreview ? -1 : 1) // Forces the non-preview to only render 1 ad
        ))); ?>
    </td>
</tr>
<?php endif; ?>
<?php if ($isPreview || (!$showAdIntro && (!empty($areas['ads'][2]) || !empty($areas['ads'][2])))): ?>
<tr>
    <td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
        <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
            'id' => 'adArea1',
            'class' => 'adArea',
            'stories' => $areas['ads'][2],
            'preview' => $isPreview,
            'filter' => ($isPreview ? -1 : 1) // Forces the non-preview to only render 1 ad
        ))); ?>
    </td>
    <td width="10">&nbsp;</td>
    <td valign="top" style="color:#606060;font-size:12px;line-height:1.4em;font-family:'Lucida Grande',Verdana,Arial;" width="273">
        <?php echo $savvy->render(new UNL_ENews_Newsletter_StoryColumn(array(
            'id' => 'adArea2',
            'class' => 'adArea',
            'stories' => $areas['ads'][0],
            'preview' => $isPreview,
            'filter' => ($isPreview ? -1 : 1) // Forces the non-preview to only render 1 ad
        ))); ?>
    </td>
</tr>
<?php endif; ?>
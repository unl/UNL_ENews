<h3 class="sec_main">
    <?php echo $context->subject; ?>
</h3>


<?php
$columnIntro = '';
$column1     = '';
$column2     = '';

foreach ($context->getStories() as $key=>$story) {

    switch($story->sort_order % 3) {
        case 1:
            $column = 'columnIntro';
            break;
        case 2:
            $column = 'column1';
            break;
        case 0:
            $column = 'column2';
            break;
    }

    $$column .= '
        <div class="story" id="story_'.$key.'">'
        . $savvy->render($story, 'ENews/Newsletter/StorySummary.tpl.php') .'
        </div>';
}
?>
<div id="newsletterWeb">
    <div id="newsColumnIntro" class="newsColumn">
        <?php echo $columnIntro; ?>
        <div class="clear"></div>
    </div>
    
    <div style="width:340px;padding:0 10px 0 0;float:left;">
        <div id="newsColumn1" class="newsColumn">
            <?php echo $column1; ?>
            <div class="clear"></div>
        </div>
    </div>
    <div style="width:340px;padding:0 0 0 10px;float:right;">
        <div id="newsColumn2" class="newsColumn">
            <?php echo $column2; ?>
            <div class="clear"></div>
        </div>
    </div>
    <div style="clear:both;display:block;text-align:center;font-size:.8em;border-top:1px solid #E0E0E0;margin-top:5px;padding-top:5px">
        Originally published <?php echo date('l F j, Y', strtotime($context->release_date)); ?>
        -
        <a href="<?php echo UNL_ENews_Controller::getURL();?>?view=submit&newsroom=<?php echo $context->newsroom_id; ?>">Submit an Item</a>
    </div>
</div>
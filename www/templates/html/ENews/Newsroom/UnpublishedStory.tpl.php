<?php
//print_r($context);
?>
<div id="story_<?php echo $context->id; ?>" class="dragItem">
    <div class="story-grip">
        <h4 class="wdn-brand"><?php echo $context->title; ?></h4>
        <span class="requestedDates">
            <span class="dateRange">
                 <?php $date = strtotime($context->request_publish_start); ?>
                 <span class="month"><?php echo date('M', $date); ?></span>
                 <span class="day"><?php echo date('j', $date); ?></span>
         </span>
        <?php if (isset($context->request_publish_end)): ?>
            <span class="dateRange">
                <?php $date = strtotime($context->request_publish_end); ?>
                <span class="month"><?php echo date('M', $date); ?></span>
                <span class="day"><?php echo date('j', $date); ?></span>
            </span>
        <?php endif; ?>
        </span>
    </div>
</div>
<?php echo $savvy->render($context, 'ENews/Newsletter/Preview/StoryData.tpl.php'); ?>

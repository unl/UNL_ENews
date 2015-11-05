<?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?> - <?php echo date('F j, Y', strtotime($context->release_date)) . "\n"; ?>
View the full version at <?php echo $context->getURL(); ?>


<?php
    $column = '';
    foreach ($context->getStories() as $key=>$story) {
        if ($story->presentation->type == 'ad') {
            $column .= $savvy->render($story, 'ENews/Newsletter/Story/Presentation/Ad.tpl.php');
        } else {
            $column .= $savvy->render($story);
        }
    }
    
    echo $column . "\n";
?>


Copyright <?php echo date('Y'); ?> University of Nebraska-Lincoln
<?php echo strip_tags($context->newsroom->footer_text); ?>
Submit Your News at <?php echo $context->newsroom->getSubmitURL()."\n"; ?>

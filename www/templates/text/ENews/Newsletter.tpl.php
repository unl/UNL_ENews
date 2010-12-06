<?php echo UNL_ENews_Newsroom::getByID($context->newsroom_id)->name; ?> - <?php echo date('l, F j, Y', strtotime($context->release_date)) . "\n"; ?>
View the full version at <?php echo UNL_ENews_Controller::getURL() . "?view=newsletter&id=".$context->id; ?>


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


Copyright <?php echo date('Y'); ?> University of Nebraska-Lincoln | Lincoln, NE 68588 | 402-472-8515<?php echo "\n"; ?>
This email produced and distributed by University Communications http://ucomm.unl.edu/<?php echo "\n"; ?>
Submit Your News at <?php echo UNL_ENews_Controller::getURL()."?view=submit&newsroom=".$context->newsroom_id."\n"; ?>
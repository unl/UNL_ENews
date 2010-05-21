Today@UNL - <?php echo date('l, F j, Y', strtotime($context->release_date)) . "\n"; ?>
View the full version at <?php echo UNL_ENews_Controller::getURL() . "?view=newsletter"; ?>


<?php
    $column = '';
    foreach ($context->getStories() as $key=>$story) {
        $column .= $savvy->render($story);
    }
    
    echo $column . "\n";
?>


Copyright <?php echo date('Y'); ?> University of Nebraska-Lincoln | Lincoln, NE 68588 | 402-472-8515<?php echo "\n"; ?>
This email produced and distributed by University Communications http://ucomm.unl.edu/<?php echo "\n"; ?>
Submit Your News at <?php echo UNL_ENews_Controller::getURL()."\n"; ?>
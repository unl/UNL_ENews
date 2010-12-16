<?php
UNL_ENews_Controller::setReplacementData('pagetitle', $context->subject);
?>
<div class="three_col left">
<h3 class="sec_main">
    <?php echo $context->subject; ?>
</h3>
<div id="newsletterWeb">
    <?php echo $savvy->render($context->getStories(), 'ENews/Newsletter/StoriesWeb.tpl.php'); ?>

    <div style="clear:both;display:block;text-align:center;font-size:.8em;border-top:1px solid #E0E0E0;margin-top:5px;padding-top:5px">
        Originally published <?php echo date('l F j, Y', strtotime($context->release_date)); ?>
        -
        <a href="<?php echo $context->newsroom->getSubmitURL(); ?>">Submit an Item</a>
    </div>
</div>
</div>
<div class="col right">
    <div class="zenbox primary">
        <h3 class="sec_main">
            <?php echo "Recent Newsletters for ".$context->newsroom->name; ?>
        </h3>
        <ul>
        <?php
        foreach (UNL_ENews_NewsletterList::getRecent($context->newsroom->id, 5) as $newsletter) {
            echo "<li> <a href=".$newsletter->getURL().">".$newsletter->subject."</a> </li>";
        }
        ?>
        </ul>
    </div>
</div>
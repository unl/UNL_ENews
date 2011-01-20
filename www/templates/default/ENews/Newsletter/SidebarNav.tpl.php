<div class="sidebar top">
    <div class="inner_sidebar">
        <div class="archives">
            <h3>
                <?php echo $context->newsroom->name; ?>
                <span>Recent Newsletters</span>
            </h3>
            <ul>
            <?php
            foreach (UNL_ENews_NewsletterList::getRecent($context->newsroom->id, 5) as $newsletter) {
                echo "<li> <a href=".$newsletter->getURL().">".date('D. M d, Y', strtotime($newsletter->release_date))."</a> </li>";
            }
            ?>
            </ul>
            <a href="<?php echo $context->newsroom->getURL();?>/archive">Full archives</a>
        </div>
    </div>
</div>
<div class="sidebar bottom">
    <div class="inner_sidebar">
        <?php echo $savvy->render($context->newsroom, 'ENews/Newsroom/SubscribeForm.tpl.php'); ?>
    </div>
</div>
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
                $clean_date = UNL_ENews_Controller::formatDate($newsletter->release_date);
                echo '<li><a href="'.$newsletter->getURL().'" title="Go to the '.$context->newsroom->name.' newsletter from '.$clean_date.'">'.$clean_date.'</a></li>';
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
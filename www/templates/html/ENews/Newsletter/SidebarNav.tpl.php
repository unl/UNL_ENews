<div class="sidebar top dcf-p-4 unl-bg-scarlet">
    <div class="archives">
        <header>
            <h3 class="dcf-inverse dcf-mb-0">
            <?php echo $context->newsroom->name; ?>
            </h3>
            <p class="dcf-inverse dcf-mt-1 dcf-subhead">Recent Newsletters</p>
        </header>
        <ul class="dcf-list-bare">
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
<div class="sidebar bottom dcf-p-4 unl-bg-light-gray">
    <?php echo $savvy->render($context->newsroom, 'ENews/Newsroom/SubscribeForm.tpl.php'); ?>
</div>

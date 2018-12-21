<section class="dcf-grid dcf-col-gap-vw dcf-row-gap-8">
    <div class="dcf-col-100% dcf-col-67%-start@md">
        <?php
        echo $savvy->render($context, 'ENews/Newsroom/EditForm.tpl.php');
        echo $savvy->render($context->getUsers());
        echo $savvy->render($context, 'ENews/Newsroom/AddUserForm.tpl.php');
        ?>
    </div>
    <div class="dcf-col-100% dcf-col-33%-end@md">
        <?php
        echo $savvy->render($context->getEmails());
        echo $savvy->render($context, 'ENews/Newsroom/AddEmailForm.tpl.php');
        ?>
    </div>
</section>

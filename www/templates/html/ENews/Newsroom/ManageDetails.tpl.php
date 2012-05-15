<div class="three_col left">
    <?php
    echo $savvy->render($context, 'ENews/Newsroom/EditForm.tpl.php');
    echo $savvy->render($context->getUsers());
    echo $savvy->render($context, 'ENews/Newsroom/AddUserForm.tpl.php');
    ?>
</div>
<div class="one_col right">
    <?php
    echo $savvy->render($context->getEmails());
    echo $savvy->render($context, 'ENews/Newsroom/AddEmailForm.tpl.php');
    ?>
</div>
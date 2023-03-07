<?php if ($context->list_domain == 'listserv.unl.edu'): ?>
If you would no longer like to receive this newsletter, visit <?php echo $context->url; ?> to unsubscribe.
<?php elseif ($context->list_domain == 'lists.unl.edu' || $context->list_domain == 'lists.nebraska.edu'): ?>
If you would no longer like to receive this newsletter, send an email to <?php echo $context->list_name; ?>-leave@<?php echo $context->list_domain; ?> to unsubscribe. Or login to <?php echo $context->url; ?> to manage your subscription.
<?php endif; ?>

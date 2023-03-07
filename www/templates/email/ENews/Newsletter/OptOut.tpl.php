<p style="text-algin:center; font-size:10px;">
<?php if ($context->list_domain == 'listserv.unl.edu'): ?>
    If you would no longer like to receive this newsletter, <a href="<?php echo $context->url; ?>">click here to unsubscribe</a>
<?php elseif ($context->list_domain == 'lists.unl.edu' || $context->list_domain == 'lists.nebraska.edu'): ?>
    If you would no longer like to receive this newsletter, send an email to <a href="mailto:<?php echo $context->list_name; ?>-leave@<?php echo $context->list_domain; ?>"><?php echo $context->list_name; ?>-leave@<?php echo $context->list_domain; ?></a> to unsubscribe. Or login to <a href="<?php echo $context->url; ?>"><?php echo $context->url; ?></a> to manage your subscription.
<?php endif; ?>
</p>



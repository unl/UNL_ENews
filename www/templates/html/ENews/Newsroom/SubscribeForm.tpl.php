<?php foreach (new LimitIterator($context->getOptOutEmails(), 0, 1) as $email) : ?>
    <div class="subscribe">
        <h3 class="clear-top"><?php echo $context->name; ?><span class="dcf-subhead">Subscribe Today!</span></h3>
        <form method="post" action="https://listserv.unl.edu/signup-anon/" id="subscribe">
            <label class="dcf-label" for="address">Email</label>
            <input class="dcf-input-text" type="email" id="address" name="ADDRESS" value="" />
            <input type="hidden" value="<?php echo $context->getURL().'?_type=subscribed';?>" name="SUCCESS_URL" />
            <input type="hidden" value="BOTH" name="LOCKTYPE" />
            <input type="hidden" name="ACTION" value="SUBMIT">
            <input type="hidden" name="LISTNAME" value="<?php echo substr($email->email, 0, strpos($email->email, '@')); ?>" />
            <input class="dcf-btn dcf-btn-primary" type="submit" value="Subscribe" name="submit" />
        </form>
    </div>
<?php endforeach; ?>

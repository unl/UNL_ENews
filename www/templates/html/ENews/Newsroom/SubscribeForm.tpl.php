<?php foreach (new LimitIterator($context->getOptOutEmails(), 0, 1) as $email) : ?>
    <div class="subscribe">
        <h3><?php echo $context->name; ?><span>Subscribe Today!</span></h3>
        <form method="get" action="http://listserv.unl.edu/signup-anon/" id="subscribe">
            <label for="address">Email</label>
            <input type="email" id="address" name="ADDRESS" value="" />
            <input type="hidden" value="<?php echo $context->getURL().'?subscribed';?>" name="SUCCESS_URL" />
            <input type="hidden" value="BOTH" name="LOCKTYPE" />
            <input type="hidden" name="LISTNAME" value="<?php echo substr($email->email, 0, strpos($email->email, '@')); ?>" />
            <input type="submit" value="Subscribe" name="submit" />
        </form>
    </div>
<?php endforeach; ?>

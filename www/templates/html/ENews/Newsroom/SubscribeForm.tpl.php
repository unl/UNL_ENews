<?php foreach (new LimitIterator($context->getOptOutEmails(), 0, 1) as $email) : ?>
    <div class="subscribe">
        <header>
            <h3 class="dcf-mb-0">Subscribe</h3>
            <p class="dcf-mt-1 dcf-subhead">Receive future newsletters from <?php echo $context->name; ?></p>
        </header>
        <form method="post" action="https://listserv.unl.edu/signup-anon/" id="subscribe">
            <label class="dcf-label dcf-sr-only" for="address">Email</label>
            <input class="dcf-input-text dcf-pl-6" type="email" id="address" name="ADDRESS" value="" />
            <input type="hidden" value="<?php echo $context->getURL().'?_type=subscribed';?>" name="SUCCESS_URL" />
            <input type="hidden" value="BOTH" name="LOCKTYPE" />
            <input type="hidden" name="ACTION" value="SUBMIT">
            <input type="hidden" name="LISTNAME" value="<?php echo substr($email->email, 0, strpos($email->email, '@')); ?>" />
            <input class="dcf-btn dcf-btn-primary dcf-mt-2" type="submit" value="Subscribe" name="submit" />
        </form>
    </div>
<?php endforeach; ?>

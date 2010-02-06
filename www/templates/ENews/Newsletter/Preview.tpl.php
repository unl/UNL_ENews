<form method="post" action="?view=newsletter&amp;id=<?php echo $context->newsletter->id; ?>">
    <input name="_type" value="newsletter" type="hidden" />
    Email Subject: <input name="subject" size="100" value="<?php echo $context->newsletter->subject; ?>" />
    <div>
    <?php echo $savvy->render($context->newsletter); ?>
    </div>
    <input type="submit" value="Save" />
</form>

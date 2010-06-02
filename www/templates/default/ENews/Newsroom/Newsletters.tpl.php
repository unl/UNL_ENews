<table class="zentable bright">
    <thead>
        <tr>
            <th>Release Date</th>
            <th>Subject</th>
            <th>Edit</th>
            <th>Send</th>
            <th>Send Preview</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($context as $newsletter): ?>
        <tr>
            <td><?php echo str_replace(' 00:00:00', '', $newsletter->release_date); ?></td>
            <td><?php echo $newsletter->subject; ?></td>
            <td><a href="?view=preview&amp;id=<?php echo $newsletter->id; ?>">Edit</a></td>
            <td>
                <?php if ($newsletter->distributed): ?>
                Sent
                <?php else: ?>
                <a href="?view=sendnews&amp;id=<?php echo $newsletter->id; ?>" onclick="return confirm('Are you sure?')">Send</a>
                <?php endif; ?>
            </td>
            <td>
                <?php echo $savvy->render($newsletter, 'ENews/Newsletter/SendPreviewForm.tpl.php'); ?>
            </td>
            <td>
                <form action="?view=newsletters" method="post" id="deletenewsletter_<?php echo $newsletter->id; ?>" style="width:120px;">
                    <input type="hidden" name="_type" value="deletenewsletter" />
                    <input type="hidden" name="newsletter_id" value="<?php echo $newsletter->id; ?>" />
                    <a href="#" onclick="if (confirm('Are you sure?')) document.getElementById('deletenewsletter_<?php echo $newsletter->id; ?>').submit();">Delete</a>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
if (count($context) > $context->options['limit']) {
    $pager = new stdClass();
    $pager->total  = count($context);
    $pager->limit  = $context->options['limit'];
    $pager->offset = $context->options['offset'];
    $pager->url    = UNL_ENews_Controller::getURL().'?view=newsletters';
    echo $savvy->render($pager, 'ENews/PaginationLinks.tpl.php');
}
?>
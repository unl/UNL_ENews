<script type="text/javascript">
WDN.jQuery('document').ready(function(){
    WDN.jQuery("a.gaStats").colorbox();
});
</script>
<table class="functionTable">
    <thead>
        <tr>
            <th>Newsletter</th>
            <th>Status</th>
            <th>Reports</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($context as $newsletter):
            $newsletterDate = strtotime($newsletter->release_date);
        ?>
        <tr>
            <td class="mainCell" style="min-width:600px;"><h5><?php echo $newsletter->subject; ?> <span class="caption">(<?php echo date('D. M d, Y', $newsletterDate); ?>)</span></h5>
                <a href="?view=preview&amp;id=<?php echo $newsletter->id; ?>" class="action edit">Edit</a>
                <?php echo $savvy->render($newsletter, 'ENews/Newsletter/SendPreviewForm.tpl.php'); ?>
                <form action="?view=newsletters" method="post" id="deletenewsletter_<?php echo $newsletter->id; ?>" style="width:120px;">
                    <input type="hidden" name="_type" value="deletenewsletter" />
                    <input type="hidden" name="newsletter_id" value="<?php echo $newsletter->id; ?>" />
                    <a href="#" onclick="if (confirm('Are you sure?')) document.getElementById('deletenewsletter_<?php echo $newsletter->id; ?>').submit();">Delete</a>
                </form>
                
            </td>
            <td align="center">
                <?php if ($newsletter->distributed): ?>
                <strong>Sent:</strong> <?php echo date('D. M d, Y', $newsletterDate); ?>
                <?php else: ?>
                <a class="action send" href="?view=sendnews&amp;id=<?php echo $newsletter->id; ?>" onclick="return confirm('This newsletter is scheduled for distribution on <?php echo date('M jS', $newsletterDate); ?>.\n\nAre you sure you want to send it now?')">Distribute Now</a>
                <?php endif; ?>
            </td>
            <td align="center">
                <?php if ($newsletter->distributed): ?>
                <a href="?view=gastats&start_date=<?php echo date('Y-m-d', $newsletterDate); ?>&end_date=<?php echo date('Y-m-d', strtotime(date('Y-m-d', $newsletterDate)."+1 week")); ?>&newsletter=<?php echo $newsletter->id; ?>&format=partial" class="gaStats">Stats</a>
                <?php endif; ?>
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
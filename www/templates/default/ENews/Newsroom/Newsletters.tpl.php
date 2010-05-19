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
            <td><?php echo $newsletter->release_date; ?></td>
            <td><?php echo $newsletter->subject; ?></td>
            <td><a href="?view=preview&amp;id=<?php echo $newsletter->id; ?>">Edit</a></td>
            <td>
                <?php if ($newsletter->distributed): ?>
                Sent
                <?php else: ?>
                <a href="?view=sendnews&amp;id=<?php echo $newsletter->id; ?>">Send</a>
                <?php endif; ?>
            </td>
            <td>
                <a href="#" onclick="WDN.jQuery(this).colorbox({inline:true, href:'#sendPreview<?php echo $newsletter->id; ?>', open:true, width:'30%', title:'Send Newsletter Preview'}); return false;">Send Preview</a>
                <div class="hidden">
                    <form id="sendPreview<?php echo $newsletter->id; ?>" action="?view=sendnews&amp;id=<?php echo $newsletter->id; ?>" method="post">
                        <input type="hidden" name="_type" value="previewnewsletter" />
                        Email Address: <input type="text" name="to" value="<?php echo UNL_ENews_Controller::getUser(true)->mail; ?>" />
                        <input type="submit" value="Send" />
                    </form>
                </div>
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
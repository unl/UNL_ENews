<table class="zentable bright">
    <thead>
        <tr>
            <th>Release Date</th>
            <th>Subject</th>
            <th>Edit</th>
            <th>Send</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($context as $newsletter): ?>
        <tr>
            <td><?php echo $newsletter->release_date; ?></td>
            <td><?php echo $newsletter->subject; ?></td>
            <td><a href="?view=newsletter&amp;id=<?php echo $newsletter->id; ?>">Edit</a></td>
            <td><a href="?view=sendnews&amp;id=<?php echo $newsletter->id; ?>">Send</a></td>
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
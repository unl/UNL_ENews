<h2>Newsletters</h2>
<table class="zentable bright two_col">
    <thead>
        <tr>
            <th>Release Date</th>
            <th>Subject</th>
            <th>Edit</th>
            <th>Send</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($context as $newsletter): ?>
        <tr>
            <td><?php echo $newsletter->release_date; ?></td>
            <td><?php echo $newsletter->subject; ?></td>
            <td><a href="?view=newsletter&amp;id=<?php echo $newsletter->id; ?>">Edit</a></td>
            <td><a href="?view=sendnews&amp;id=<?php echo $newsletter->id; ?>">Send</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
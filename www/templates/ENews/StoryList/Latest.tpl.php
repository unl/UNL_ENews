<div class="three_col left">
    <h1>Latest E-News</h1>
    <ul>
        <?php foreach($context as $item) :?>
        <li><?php echo $savvy->render($item, 'ENews/Newsletter/Story.tpl.php'); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="col right" style="overflow:hidden;">
    <a href="?view=submit">Submit News</a>
</div>
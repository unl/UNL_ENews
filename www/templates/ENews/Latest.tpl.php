<h1>Latest E-News</h1>
<ul>
    <?php foreach($context as $item) :?>
    <li><?php echo $savvy->render($item); ?></li>
    <?php endforeach; ?>
</ul>
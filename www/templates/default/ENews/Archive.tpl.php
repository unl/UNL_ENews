<h3 class="sec_main">
    <?php echo "Archive for ".$context->newsroom->name; ?>
</h3>

<ul>
<?php 
foreach ($context as $newsletter) {
    if ($newsletter->distributed) {
        $clean_date = date('D. M d, Y', strtotime($newsletter->release_date));
        echo '<li>
        <a href="'.$newsletter->getURL().'">'.$newsletter->subject.'</a> <span class="release_date caption" title="'.$newsletter->release_date.'">('.$clean_date.')</span></li>';
    }
}
?>
</ul>
<?php
if (count($context) > $context->options['limit']) {
    $pager = new stdClass();
    $pager->total  = count($context);
    $pager->limit  = $context->options['limit'];
    $pager->offset = $context->options['offset'];
    $pager->url    = $context->newsroom->getURL().'/archive';
    echo $savvy->render($pager, 'ENews/PaginationLinks.tpl.php');
}
?>
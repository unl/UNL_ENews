<ul>
<?php 
foreach ($context as $newsletter) {
    if ($newsletter->distributed){
        echo "<li> <a href=".$newsletter->getURL().">".$newsletter->subject."</a> </li>";
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
    $pager->url    = $context->newsroom->getURL().'/archive?';
    echo $savvy->render($pager, 'ENews/PaginationLinks.tpl.php');
}
?>
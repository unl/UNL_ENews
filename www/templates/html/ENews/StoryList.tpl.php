<div class="stories">
<?php
foreach($context as $item) {
    echo $savvy->render($item, 'ENews/Newsletter/Story/Summary.tpl.php');
}
?>
</div>
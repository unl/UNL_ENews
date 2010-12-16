<ul>
<?php 
foreach ($context->newsletters as $newsletter) {
    if ($newsletter->distributed){
        echo "<li> <a href=".$newsletter->getURL().">".$newsletter->subject."</a> </li>";
    }
}
?>
</ul>
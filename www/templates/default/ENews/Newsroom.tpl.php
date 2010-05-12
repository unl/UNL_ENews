<h2>Details for the <?php echo $context->name.' ('.$context->shortname.')'; ?> Newsroom</h2>

<h3>Users</h3>
<ul>
<?php
foreach ($context->getUsers() as $user) {
    echo '<li>'.$user->uid.'</li>';
}
?>
</ul>
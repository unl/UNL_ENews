<h3>Users</h3>
<ul id="userList">
<?php
foreach ($context as $user) {
    echo '<li><img class="profile_pic medium" src="https://directory.unl.edu/avatar/'.$user->uid.'?s=medium" alt="Profile photo of '.$user->uid.'" />
    <form action="'.UNL_ENews_Controller::getURL().'?view=newsroom" method="post">
        <input type="hidden" name="newsroom_id" value="'.$parent->context->id.'" />
        <input type="hidden" name="_type" value="removeuser" />
        <input type="hidden" name="user_uid" value="'.$user->uid.'" />
        <input type="submit" value="Remove" />
    </form>
    <span class="uid">'.$user->uid.'</span>
    </li>';
}
?>
</ul>

<h3>Users</h3>
<ul id="userList">
<?php
foreach ($context as $user) {
    echo '<li><img class="profile_pic medium" src="http://planetred.unl.edu/pg/icon/unl_'.$user->uid.'/medium/" alt="Planet Red photo for '.$user->uid.'" />
    <form action="'.UNL_ENews_Controller::getURL().'?view=newsroom" method="post">
        <input type="hidden" name="newsroom_id" value="'.$parent->context->id.'" />
        <input type="hidden" name="_type" value="removeuser" />
        <input type="hidden" name="user_uid" value="'.$user->uid.'" />
        <input type="submit" value="Remove" />
    </form>
    <span class="uid">('.$user->uid.')</span>
    </li>';
}
?>
</ul>

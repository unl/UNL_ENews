<h3>Users</h3>
<ul id="userList">
<?php foreach ($context as $user): ?>
    <li>
      <img class="profile_pic medium" src="https://directory.unl.edu/avatar/<?php echo $user->uid ?>?s=medium" alt="Profile photo of <?php echo $user->uid ?>" />
      <form action="<?php echo UNL_ENews_Controller::getURL() ?>?view=newsroom" method="post">
          <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
          <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
          <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
          <input type="hidden" name="newsroom_id" value="<?php echo $parent->context->id ?>" />
          <input type="hidden" name="_type" value="removeuser" />
          <input type="hidden" name="user_uid" value="<?php echo $user->uid ?>" />
          <input type="submit" value="Remove" />
      </form>
      <span class="uid"><?php echo $user->uid ?></span>
    </li>
<?php endforeach; ?>
</ul>

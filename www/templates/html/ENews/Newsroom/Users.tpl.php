<h3 class="dcf-mt-6">Users</h3>
<ul id="userList" class="dcf-list-inline unl-bg-lighter-gray dcf-p-2">
<?php foreach ($context as $user): ?>
    <li class="dcf-p-1 dcf-relative">
      <img class="dcf-d-block dcf-h-10 dcf-w-10" src="https://directory.unl.edu/avatar/<?php echo $user->uid ?>?s=medium" alt="Profile photo of <?php echo $user->uid ?>" />
      <form class="dcf-form dcf-absolute dcf-d-none" action="<?php echo UNL_ENews_Controller::getURL() ?>?view=newsroom" method="post">
          <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
          <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
          <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
          <input type="hidden" name="newsroom_id" value="<?php echo $parent->context->id ?>" />
          <input type="hidden" name="_type" value="removeuser" />
          <input type="hidden" name="user_uid" value="<?php echo $user->uid ?>" />
          <input class="dcf-btn dcf-btn-primary" type="submit" value="Remove" />
      </form>
      <span class="dcf-txt-xs"><?php echo $user->uid ?></span>
    </li>
<?php endforeach; ?>
</ul>

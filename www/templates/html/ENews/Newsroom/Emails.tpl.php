<h3>Email Addresses</h3>
<ul id="emailList" class="unl-bg-lightest-gray">
    <?php foreach ($context as $email): ?>
        <li>
            <span class="email" title="<?php echo $email->email ?>"><?php echo $email->email ?></span>
            <?php if ($email->optout): ?>
                <span class="details">Can Unsubscribe</span>
            <?php endif ?>

            <?php if ($email->newsletter_default): ?>
                <span class="details">Default</span>
            <?php endif ?>
            
            <form action="<?php echo UNL_ENews_Controller::getURL() ?>?view=newsroom" method="post">
                <?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
                <input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
                <input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
                <input type="hidden" name="email_id" value="<?php echo $email->id ?>" />
                <input type="hidden" name="_type" value="removeemail" />
                <input type="submit" value="X" />
            </form>
        </li>
    <?php endforeach; ?>
</ul>

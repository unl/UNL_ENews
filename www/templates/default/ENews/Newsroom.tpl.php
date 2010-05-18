<div class="two_col left">
    <script type="text/javascript">
    //<![CDATA[
        WDN.jQuery(document).ready(function(){
             WDN.initializePlugin('zenform');
        });
    //]]>
    </script>
    <h3 class="zenform">Edit details for the current newsroom</h3>
    <form class="zenform" action="?view=manager" method="post">
        <input type="hidden" name="_type" value="newsroom" />
        <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
        <fieldset>
                <legend>Newsroom Details</legend>
                <ol>
                    <li>
                        <label for="name">
                            <span class="required">*</span>
                            Name
                            <span class="helper">Title for this newsroom</span>
                        </label>
                        <input type="text" id="name" name="name" value="<?php echo $context->name; ?>" />
                    </li>
                    <li>
                        <label for="shortname">
                            <span class="required">*</span>
                            Short Name
                            <span class="helper">A short string used in the web address</span>
                        </label>
                        <input type="text" id="shortname" name="shortname" value="<?php echo $context->shortname; ?>" />
                    </li>
                    <li>
                        <label for="website">
                            Website
                            <span class="helper">Website associated with this newsroom</span>
                        </label>
                        <input type="text" id="website" name="website" value="<?php echo $context->website; ?>" />
                    </li>
                    <li>
                        <label for="allow_submissions">Allow Submissions
                        <span class="helper">Can users send news items for review?</span>
                        </label>
                        <input type="checkbox" id="allow_submissions" name="allow_submissions" /> Yes
                    </li>
                    <li>
                        <label for="email_lists">Email Lists
                            <span class="helper">The email addresses to send newsletters to</span>
                        </label>
                        <textarea id="email_lists" name="email_lists" rows="4" cols="auto"><?php echo $context->email_lists; ?></textarea>
                    </li>
                </ol>
        </fieldset> 
        <input type="submit" name="submit" value="Submit" />
    </form>
</div>
<div class="two_col right">
    <h3>Users</h3>
    <ul>
    <?php
    foreach ($context->getUsers() as $user) {
        echo '<li>'.$user->uid.'
        <form action="?view=newsroom" method="post">
            <input type="hidden" name="newsroom_id" value="'.$context->id.'" />
            <input type="hidden" name="_type" value="removeuser" />
            <input type="hidden" name="user_uid" value="'.$user->uid.'" />
            <input type="submit" value="Remove" />
        </form></li>';
    }
    ?>
    </ul>
    <form action="?view=newsroom" method="post">
        <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
        <input type="hidden" name="_type" value="adduser" />
        <input type="text" name="user_uid" />
        <input type="submit" value="Add User" />
    </form>
</div>
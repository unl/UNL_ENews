<div class="three_col left">
    <script type="text/javascript">
    //<![CDATA[
        WDN.jQuery(document).ready(function(){
             WDN.initializePlugin('zenform');
             WDN.loadCSS('<?php echo UNL_ENews_Controller::getURL();?>css/newsroom.css');
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
                        <input type="checkbox" id="allow_submissions" name="allow_submissions" <?php echo ($context->allow_submissions)? 'checked="checked"': ''; ?> /> Yes
                    </li>
                </ol>
        </fieldset> 
        <input type="submit" name="submit" value="Submit" />
    </form>
    <h3 class="sec_main">Users</h3>
    <ul id="userList">
    <?php
    foreach ($context->getUsers() as $user) {
        echo '<li><img class="profile_pic medium" src="http://planetred.unl.edu/pg/icon/unl_'.$user->uid.'/medium/" />
        <form action="?view=newsroom" method="post">
            <input type="hidden" name="newsroom_id" value="'.$context->id.'" />
            <input type="hidden" name="_type" value="removeuser" />
            <input type="hidden" name="user_uid" value="'.$user->uid.'" />
            <input type="submit" value="Remove" />
        </form>
        <span class="uid">('.$user->uid.')</span>
        </li>';
    }
    ?>
    </ul>
    <form action="?view=newsroom" method="post" id="addUser" class="addData">
        <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
        <input type="hidden" name="_type" value="adduser" />
        <input type="text" name="user_uid" />
        <input type="submit" value="Add User" />
    </form>
</div>
<div class="one_col right">
<h3 class="sec_main">Email Addresses</h3>
    <p>Available Email Listservs</p>
    <ul id="emailList">
        <?php
        foreach ($context->getEmails() as $email) {
            echo '<li>'.$email->email.'
		        <form action="?view=newsroom" method="post">
		            <input type="hidden" name="email_id" value="'.$email->id.'" />
		            <input type="hidden" name="_type" value="removeemail" />
		            <input type="submit" value="X" />
		        </form>
        </li>';
        }
        ?>
    </ul>
    <form action="?view=newsroom" method="post" id="addEmail" class="addData">
        <input type="hidden" name="newsroom_id" value="<?php echo $context->id; ?>" />
        <input type="hidden" name="_type" value="addemail" />
        <input type="text" name="email" />
        <input type="submit" value="Add Email" />
    </form>
</div>
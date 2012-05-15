<h3 class="sec_main">Email Addresses</h3>
<ul id="emailList">
    <?php
    foreach ($context as $email) {
        echo '<li><span class="email" title="'.$email->email.'">'.$email->email.'</span>';
            if ($email->optout){
                echo '<span class="details">Opt Out</span>';
            };
            
            if ($email->newsletter_default) {
                echo '<span class="details">Default</span>';
            }
            
            echo '<form action="'.UNL_ENews_Controller::getURL().'?view=newsroom" method="post">
                <input type="hidden" name="email_id" value="'.$email->id.'" />
                <input type="hidden" name="_type" value="removeemail" />
                <input type="submit" value="X" />
            </form>
    </li>';
    }
    ?>
</ul>

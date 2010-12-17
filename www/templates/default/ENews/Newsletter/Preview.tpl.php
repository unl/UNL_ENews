<script type="text/javascript">
    WDN.loadJS("/wdn/templates_3.0/scripts/plugins/ui/jQuery.ui.js",function(){
        WDN.jQuery("#releaseDate").datepicker({
            showOn: 'both',
            buttonImage: '/wdn/templates_3.0/css/content/images/mimetypes/x-office-calendar.png',
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true
        });
        WDN.loadJS("<?php echo UNL_ENews_Controller::getURL();?>js/preview.js", function(){
            preview.initialize();
        });
    });
    WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css");

    WDN.jQuery(function($){
        $('h3 a.showHide').click(function() {
            $(this).parent('h3').nextUntil('h3').slideToggle();
            $(this).toggleClass('show');
            return false;
        });
    });
</script>

<div id="newsletterDetails">
	<form method="post" action="?view=preview&amp;id=<?php echo $context->newsletter->id; ?>">
	    <fieldset style="float:left">
	    <legend>Your Newsletter</legend>
	    <ol style="margin-top:0">
	        <li>
	            <input type="hidden" name="_type" value="newsletter" />
	            <input type="hidden" name="id" id="id" value="<?php echo $context->newsletter->id; ?>" />
	            <label for="emailSubject">Email Subject<span class="required">*</span><span class="helper">Include story keywords!</span></label>
	            <input name="subject" type="text" value="<?php echo $context->newsletter->subject; ?>" id="emailSubject" />
	        </li>
	        <li>
	            <label for="releaseDate">Release Date</label>
	            <input class="datepicker" name="release_date" type="text" size="10" value="<?php echo str_replace(' 00:00:00', '', $context->newsletter->release_date); ?>" id="releaseDate" />
	        </li>
	    </ol>
	    </fieldset>
	    <input type="submit" name="submit" value="Save" disabled="disabled" />
	    <a class="action preview" href="#" onclick="WDN.jQuery(this).colorbox({inline:true, href:'#sendPreview<?php echo $context->newsletter->id; ?>', open:true, width:'30%', title:'Send Newsletter Preview'}); return false;">Send Preview</a>
	</form>
	<?php echo $savvy->render($context->newsletter, 'ENews/Newsletter/SendPreviewForm.tpl.php'); ?>
    <div class="email_addresses">
        <h5>Email Listservs</h5>
        <ul>
            <?php
            $existing_emails = $context->newsletter->getEmails()->getArrayCopy(); 
            foreach ($context->newsletter->newsroom->getEmails() as $email):
                $checked = false;
                if (in_array($email->id, $existing_emails)) {
                    $checked = true;
                }
            ?>
            <li>
            	<form class="emailIndicator">
                	<input type="checkbox" id="email_<?php echo $email->id; ?>" <?php if ($checked) echo 'checked="checked"'; ?> />
                	<label for="email_<?php echo $email->id; ?>" >
                		<?php echo $email->email; ?>
                	</label>
                </form>
                <form action="?view=preview&amp;id=<?php echo $context->newsletter->id; ?>" method="post" class="remove email">
                    <input type="hidden" name="newsletter_id" value="<?php echo $context->newsletter->id; ?>" />
                    <input type="hidden" name="newsroom_email_id" value="<?php echo $email->id; ?>" />
                    <input type="hidden" name="_type" value="removenewsletteremail" />
                    <input type="submit" value="Remove" />
                </form>
                <form action="?view=preview&amp;id=<?php echo $context->newsletter->id; ?>" method="post" class="add email">
                    <input type="hidden" name="newsletter_id" value="<?php echo $context->newsletter->id; ?>" />
                    <input type="hidden" name="newsroom_email_id" value="<?php echo $email->id; ?>" />
                    <input type="hidden" name="_type" value="addnewsletteremail" />
                    <input type="submit" value="Add" />
                </form>
                
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<div class="col left" id="drag_story_list">
<?php $stories = $context->getRaw('available_stories'); ?>
<?php foreach (array('news', 'event', 'ad') as $type): ?>
    <div id="<?php echo $type; ?>Available">
        <h3><?php echo ucfirst($type); ?> <span>Submissions</span><a href="#" class="showHide">Hide</a></h3>
        <div class="storyItemWrapper">
            <?php echo $savvy->render($stories->setType($type)); ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
<div class="three_col right">
    <?php echo $savvy->render($context->newsletter, 'templates/email/ENews/Newsletter.tpl.php'); ?>
</div>

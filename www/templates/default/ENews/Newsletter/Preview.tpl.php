<?php $savvy->setTemplatePath(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/www/templates/email');?>

<script type="text/javascript">
    WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
    WDN.loadJS("/wdn/templates_3.0/scripts/plugins/ui/jQuery.ui.js");
    WDN.loadJS("js/functions.js");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css");
    WDN.loadCSS("/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css");
    WDN.jQuery(document).ready(function($){
        //We don't want to see the links right now but we will still want them in the actual email
        WDN.jQuery("#maincontent h4 a").each(function(){
            var $t = WDN.jQuery(this);
            $t.after($t.text());
            $t.remove();
        });
        $("#releaseDate").datepicker({showOn: 'both', buttonImage: '/wdn/templates_3.0/css/content/images/mimetypes/x-office-calendar.png', buttonImageOnly: true});
    });
</script>

<form class="enews energetic" method="post" action="?view=preview&amp;id=<?php echo $context->newsletter->id; ?>">
    <fieldset style="background:url('images/process_step_fade.jpg') repeat-x top #fbfaf6;">
    <legend>Your Newsletter</legend>
    <ol>
        <li>
            <input type="hidden" name="_type" value="newsletter" />
            <input type="hidden" name="id" id="id" value="<?php echo $context->newsletter->id; ?>" />
            <label for="emailSubject">Email Subject<span class="required">*</span></label>
            <input name="subject" type="text" value="<?php echo $context->newsletter->subject; ?>" id="emailSubject" />
            <label for="releaseDate">Release Date</label>
            <input class="datepicker" name="release_date" type="text" size="10" value="<?php echo $context->newsletter->release_date; ?>" id="releaseDate" />
        </li>
    </ol>
    
    </fieldset>
    <p class="submit"><input type="submit" name="submit" value="Save" /></p>
    
</form>
    <div class="col left">
        <div class="zenbox energetic" id="drag_story_list" style="margin-top:200px;"> 
            <h3>Available News</h3>
            <?php
            $story_available = false;
            foreach ($context->available_stories->getIterator() as $story):

                if (!$context->newsletter->hasStory($story)):
                    $story_available = true; ?>
                    <div id="drag_story_<?php echo $story->id; ?>" class="dragItem">
                        <?php echo $savvy->render($story, 'ENews/Newsletter/Story.tpl.php'); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (!$story_available) {
                echo "<p>Sorry, no unused/available stories.</p>";
            } ?>
        </div>
    </div>
    <div class="three_col right">
        <?php echo $savvy->render($context->newsletter); ?>
    </div>

<a href="#" onclick="WDN.jQuery(this).colorbox({inline:true, href:'#sendPreview<?php echo $context->id; ?>', open:true, width:'30%', title:'Send Newsletter Preview'}); return false;">Send Preview</a>
<div class="hidden">
    <form id="sendPreview<?php echo $context->id; ?>" action="?view=sendnews&amp;id=<?php echo $context->id; ?>" method="post">
        <input type="hidden" name="_type" value="previewnewsletter" />
        Email Address: <input type="text" name="to" value="<?php echo UNL_ENews_Controller::getUser(true)->mail; ?>" />
        <input type="submit" value="Send" />
    </form>
</div>
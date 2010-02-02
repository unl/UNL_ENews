<?php
function getValue($object, $field)
{
    if (isset($object->$field)) {
        return htmlentities($object->$field, ENT_QUOTES);
    }
    
    if (isset($_POST[$field])) {
        return htmlentities($_POST[$field], ENT_QUOTES);
    }
    
    return '';
}
?>
<script type="text/javascript">
WDN.loadCSS('http://www.unl.edu/wdn/templates_3.0/css/content/forms.css');
</script>
<form class="cool" action="?" method="post">
    <fieldset>
        <legend>E-News/Newstips Submission</legend>
        <ol>
            <li><label for="title" class="element"><span class="required">*</span>Headline or Title</label><div class="element"><input id="title" name="title" type="text" value="<?php echo getValue($context, 'title'); ?>" /></div></li>
            <li><label for="description" class="element">Description</label><div class="element"><textarea id="description" name="description" cols="60" rows="5"><?php echo getValue($context, 'description'); ?></textarea></div></li>
            <li><label for="event_date" class="element"><span class="required">*</span>Date and Time:</label><div class="element"><input id="event_date" name="event_date" type="text" size="10"  value="<?php echo getValue($context, 'event_date'); ?>" /></div></li>
            <li><label for="email" class="element"><span class="required">*</span>Email</label><div class="element"><input id="email" name="email" type="text" /></div></li>
            <li><label class="element">I Can Has Cheezburger?</label><div class="element"><input name="helpful" value="1" type="radio" id="cheezyes" /><label for="cheezyes">Yes</label><input name="helpful" value="0" type="radio" id="cheezno" /><label for="cheezno">No</label></div></li>
            <li class="reqnote"><label class="element">&nbsp;</label><span class="required">*</span> denotes required field</li>
        </ol>
    </fieldset>
    <p class="submit"><input type="submit" id="submit" name="submit" value="Submit" /></p>
</form>

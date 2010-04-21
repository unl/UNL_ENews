<div class="three_col left">
<h3 class="sec_main"><?php echo $context->title; ?></h3>
<p>
<?php 
foreach ($context->getFiles() as $file) {
    if (preg_match('/^image/', $file->type) && $file->use != 'thumbnail') {
        echo '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
             . $file->id
             . '" style="max-width:300px; margin-left:15px; float:right;" class="frame" alt="'.$file->name.'" />';
    }
}

echo $context->fulltext;
?>
</div>
<div class="col right">

</div>
<div class="three_col">
<h4 style=""><?php echo $context->title; ?></h4>
<p>
<?php 
foreach ($context->getFiles() as $file) {
    if (preg_match('/^image/', $file->type)) {
        echo '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
             . $file->id
             . '" style="max-width:300px; margin-right:15px;" align="left" class="frame" alt="'.$file->name.'" />';
    }
}

echo $context->description;
?>
</div>
<div class="col right">

</div>
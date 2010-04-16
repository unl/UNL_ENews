<h4><a href="<?php echo UNL_ENews_Controller::getURL(); ?>?view=story&id=<?php echo $context->id; ?>"><?php echo $context->title; ?></a></h4>
<p>
<?php 
foreach ($context->getFiles() as $file) {
    if (preg_match('/^image/', $file->type)) {
        echo '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
             . $file->id
             . '" style="max-width:72px; margin-right:15px;" align="left" />';
    }
}

echo $context->description;
?>
</p>
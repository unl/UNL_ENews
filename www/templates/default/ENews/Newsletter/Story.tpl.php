<style type="text/css">


</style>


<h4><a href="<?php echo UNL_ENews_Controller::getURL(); ?>?view=story&id=<?php echo $context->id; ?>"><?php echo $context->title; ?></a></h4>
<p>
<?php 
foreach ($context->getFiles() as $file) {
    if ($file->use == 'thumbnail') {
        echo '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
             . $file->id
             . '" style="max-width:72px; margin-right:15px;" align="left" />';
    }
}

echo $context->description;
?>
</p>
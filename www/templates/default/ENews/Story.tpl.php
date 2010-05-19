<div class="three_col left">
<h3 class="sec_main"><?php echo $context->title; ?></h3>

<div style="float:right;width:310px;margin:0 0 20px 10px;">
<?php 
foreach ($context->getFiles() as $file) {
    if (preg_match('/^image/', $file->type) && $file->use_for != 'thumbnail') {
        echo '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
             . $file->id
             . '" style="max-width:300px;float:left;" class="frame" alt="'.$file->name.'" />';
    }
}
?>
</div>

<?php $full = trim($context->full_article); ?>
<?php if (!empty($full)) : ?>
    <div style="background:#f0f0f0;width:350px;margin:0 10px 15px 0;padding:10px;font-style:italic;">
    <?php echo nl2br($context->description); ?>
    </div>
    <p>
    <?php echo nl2br(UNL_ENews_Controller::makeClickableLinks($context->full_article)); ?>
    </p>
<?php else : ?>
    <?php echo nl2br($context->description); ?>
<?php endif ?>

</div>
<div class="col right">

</div>
<?php
UNL_ENews_Controller::setReplacementData('pagetitle', $context->story->title);
?>
<div class="three_col left">
<h3 class="sec_main"><?php echo $context->story->title; ?></h3>

<div style="float:right;width:310px;margin:0 0 20px 10px;">
<?php 
foreach ($context->story->getFiles() as $file) {
    if (preg_match('/^image/', $file->type) && $file->use_for != 'thumbnail') {
        echo '<img src="'.UNL_ENews_Controller::getURL().'?view=file&amp;id='
             . $file->id
             . '" style="max-width:300px;float:left;" class="frame" alt="'.$file->name.'" />';
    }
}
?>
</div>
<?php $full = trim($context->story->full_article); ?>
<?php if (!empty($full)) : ?>
    <p>
    <?php echo nl2br(UNL_ENews_Controller::makeClickableLinks($context->story->full_article)); ?>
    </p>
<?php else : ?>
    <?php echo nl2br($context->story->description); ?>
<?php endif ?>
<?php
if (($context->story->website)) { ?>
<p>
More details at: <a href="<?php echo $context->story->website; ?>" title="Go to the supporting webpage"><?php echo $context->story->website; ?></a>
</p>
<?php 
}
?>
</div>
<div class="col right">

<div class="zenbox primary">
  <h3><?php echo $context->newsroom->name; ?></h3>
  <b><?php echo $context->newsletter->subject; ?></b>
  <p><?php echo $context->newsletter->intro; ?></p>
  <ul>
  <?php 
  foreach ($context->newsletter->getStories() as $key=>$story) {
  	echo '<li><a href="'.UNL_ENews_Controller::getURL().$context->newsroom->shortname.'/'.$context->newsletter->id.'/'.$story->id.'">'.$story->title.'</a></li>';
  }
  ?>
  </ul>
</div>

</div>

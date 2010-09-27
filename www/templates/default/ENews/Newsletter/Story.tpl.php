<div class="three_col left">
    <?php echo $savvy->render($context->story); ?>
</div>
<div class="col right">

    <div class="zenbox primary">
      <h3><?php echo $context->newsroom->name; ?></h3>
      <strong><?php echo $context->newsletter->subject; ?></strong>
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

<?php
$newsletter_url = $context->newsletter->getURL();
?>
<div class="three_col left">
    <?php echo $savvy->render($context->story); ?>
</div>
<div class="col right">

    <div class="zenbox primary">
      <h3><?php echo $context->newsroom->name; ?></h3>
      <a href="<?php echo $newsletter_url; ?>"><?php echo $context->newsletter->subject; ?></a>
      <p><?php echo $context->newsletter->intro; ?></p>
      <ul>
      <?php 
      foreach ($context->newsletter->getStories() as $key=>$story) {
          echo '<li><a href="'.$newsletter_url.'/'.$story->id.'">'.$story->title.'</a></li>';
      }
      ?>
      </ul>
    </div>

</div>

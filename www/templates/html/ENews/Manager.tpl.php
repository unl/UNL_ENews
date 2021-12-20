<div class="dcf-grid dcf-mb-6">
    <div class="dcf-col-100% dcf-col-33%-end@md dcf-2nd@md">
        <?php
        if ($context->actionable) {
            echo $savvy->render($context->actionable[0]);
        }
        ?>
    </div>
    <div class="dcf-col-100% dcf-col-67%-start@md dcf-1st@md">
        <h3>
            <?php echo $context->newsroom->name;?>
             <a class="rsslink" href="<?php echo $context->newsroom->getURL();?>/latest?format=rss">RSS</a>
        </h3>
    </div>
</div>

<div class="dcf-tabs-faked dcf-mt-4">
	<?php
    // Render DCF Tabs markup with PHP since this use case is not supported
	const VALID_STATUSES = array('pending', 'approved', 'archived');
	$status = !empty($_GET['status']) && in_array(strtolower($_GET['status']), VALID_STATUSES) ? strtolower(htmlentities($_GET['status'])) : 'pending';
	foreach (VALID_STATUSES as $type):
        $selected = $status === $type ? 'tabindex="-1" aria-selected="true"' : 'tabindex="0"';
		?>
        <li class="dcf-tabs-list-item dcf-mb-0" role="presentation"><a id="tab-<?php echo $type; ?>" href="<?php echo $context->newsroom->getURL();?>/manage?status=<?php echo $type; ?>" class="dcf-tab dcf-d-block" role="tab"  <?php echo $selected; ?> data-panel-id="status-<?php echo $type; ?>"><?php echo ucfirst($type); ?><small class="dcf-badge dcf-badge-pill"><?php echo count($context->newsroom->getStories($type)); ?></small></a></li>
	<?php endforeach; ?>
    </ol>
    <section id="status-<?php echo $status; ?>" role="tabpanel" tabindex="-1" class="dcf-tabs-panel" aria-labelledby="tab-<?php echo $status; ?>">
        <h4><?php echo ucfirst($status); ?> News Items</h4>
	    <?php
	    if ($context->actionable && isset($context->actionable[1])) {
		    echo $savvy->render($context->actionable[1]);
	    } else {
		    echo '<div class="dcf-w-100%">No gnews is good gnews with Gary Gnu.</div>';
	    }
	    ?>
    </section>
</div>
<script>
  window.addEventListener('inlineJSReady', function() {
    // Load tabs plugin load tabs WDN custom CSS
    WDN.initializePlugin('tabs');
  }, false);
</script>
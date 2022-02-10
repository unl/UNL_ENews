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

<?php
const VALID_STATUSES = array('pending', 'approved', 'archived');
$status = !empty($_GET['status']) && in_array(strtolower($_GET['status']), VALID_STATUSES) ? strtolower(htmlentities($_GET['status'])) : 'pending';
$searchTerm = !empty($_GET) ? filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING) : '';
?>
<div class="dcf-notice dcf-notice-warning" hidden data-no-close-button>
    <h2>Search Notice</h2>
    <div><p>Your search query must be at least 3 characters.</p></div>
</div>
<div class="dcf-mt-6">
    <form id="search-form" class="dcf-form" id="dcf-input-group-button" method="get">
        <input type="hidden" name="status" value="<?php echo $status; ?>">
        <label for="dcf-input-group-button-input">Search News Items</label>
        <div class="dcf-input-group">
            <input id="dcf-input-group-button-input" name="term" type="search" autocomplete="off" value="<?php echo $searchTerm; ?>">
            <button class="dcf-btn dcf-btn-primary" type="submit">Search</button>
        </div>
    </form>
</div>

<div class="dcf-tabs-faked dcf-mt-4">
    <ol class="dcf-tabs-list dcf-list-bare dcf-mb-0" role="tablist">
    <?php
    // Render DCF Tabs markup with PHP since this use case is not supported
    $termParam = !empty($searchTerm) ? '&term=' . $searchTerm : '';
    foreach (VALID_STATUSES as $type):
        $selected = $status === $type ? 'tabindex="-1" aria-selected="true"' : 'tabindex="0"';
        ?>
        <li class="dcf-tabs-list-item dcf-mb-0" role="presentation"><a id="tab-<?php echo $type; ?>" href="<?php echo $context->newsroom->getURL();?>/manage?status=<?php echo $type . $termParam; ?>" class="dcf-tab dcf-d-block" role="tab"  <?php echo $selected; ?> data-panel-id="status-<?php echo $type; ?>"><?php echo ucfirst($type); ?> <small class="dcf-badge dcf-badge-pill"><?php echo count($context->newsroom->getStories($type, $searchTerm)); ?></small></a></li>
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
<?php
$savvy->loadScriptDeclaration(trim("
    WDN.initializePlugin('tabs');
    var searchForm = document.getElementById('search-form');
    searchForm.addEventListener('submit', function(event) {
        var term = searchForm.elements['term'].value;
        if (term.length > 0 && term.length < 3) {
            WDN.initializePlugin('notice');
            event.preventDefault();
        }
    });
"));
?>

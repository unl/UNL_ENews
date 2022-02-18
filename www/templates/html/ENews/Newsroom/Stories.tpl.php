<?php
$showSearch = false;
$status = 'approved';
if (isset($parent->context->options['status'])) {
    $status = $parent->context->options['status'];
}
if ($parent->context->options['model'] === 'UNL_ENews_User_StoryList') {
	$showSearch = true;
    $status = 'none';
}

$term = '';
if (isset($parent->context->options['term'])) {
	$term = $parent->context->options['term'];
}

if (count($context) == 0 && empty($term)) {
    echo '<div class="four_col">No Gnews is Good Gnews with Gary Gnu!</div>';
    return;
}
$cacheBust = uniqid();
$savvy->loadScript(UNL_ENews_Controller::getURL() . "/js/manager.js?ver=" . $cacheBust);
$savvy->loadScriptDeclaration("
    WDN.loadJS(\"/wdn/templates_5.3/js/compressed/plugins/ui/jquery-ui.js\");
    WDN.jQuery(document).ready(function() {
        manager.initialize();
    });
");
?>


<?php
if (!empty($context->options['story_id'])) {
  $savvy->loadScriptDeclaration("WDN.initializePlugin('notice');");
?>
<div class="dcf-notice dcf-notice-success" hidden>
    <?php if (!empty($context->options['newsroom'])): ?>
    <h2>Thanks for your submission!</h2>
    <div>
        <p>Your article is now in our queue. We will review, adapt and incorporate to the best of our abilities. If we have any questions, we'll contact you. If you have any questions, please contact us.</p>

        <h2 class="dcf-mt-4 dcf-mb-2 dcf-txt-h6" style="color: var(--bg-white);">Have more news you'd like to share?</h3>
        <p><a href="<?php echo UNL_ENews_Newsroom::getById($context->options['newsroom'])->getSubmitURL(); ?>">Submit another story&hellip;</a></p>
    </div>
    <?php else: ?>
    <h2>Submission Saved!</h2>
    <div><p>Your article has been saved.</p></div>
    <?php endif; ?>
</div>
<?php echo $savvy->render($context, 'ENews/Confirmation/Submission.tpl.php'); ?>
<?php } //end if ?>

<?php
$searchTerm = !empty($_GET) ? filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING) : '';
$view = !empty($_GET) ? filter_input(INPUT_GET, 'view', FILTER_SANITIZE_STRING) : '';
?>

<?php if ($showSearch): ?>
<div class="dcf-d-none" id="search-form-notice">
    <div class="dcf-notice dcf-notice-warning" hidden data-no-close-button>
        <h2>Search Notice</h2>
        <div><p>Your search query must be at least 3 characters.</p></div>
    </div>
</div>
<div class="dcf-mb-3">
    <form id="search-form" class="dcf-form" id="dcf-input-group-button" method="get">
        <input type="hidden" name="view" value="<?php echo $view; ?>"
        <label for="dcf-input-group-button-input">Search News Submissions</label>
        <div class="dcf-input-group">
            <input id="dcf-input-group-button-input" name="term" type="search" autocomplete="off" value="<?php echo $searchTerm; ?>">
            <button class="dcf-btn dcf-btn-primary" type="submit">Search</button>
        </div>
    </form>
</div>
<?php endif; ?>

<form id="enewsManage" name="enewsManage" class="dcf-form energetic" method="post" action="<?php echo $context->getManageURL(); ?>">
<?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
<input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
<input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">
<input type="hidden" name="_type" value="change_status" />

<?php if ($parent->context->options['model'] === 'UNL_ENews_Manager') : ?>
<input type="hidden" name="newsroom" value="<?php echo $parent->context->options['newsroom']; ?>" />
<input type="hidden" name="status" value="<?php echo $status; ?>" />
<?php endif ?>

<?php if (count($context) > 0): ?>
<div class="storyAction">
    <div class="storyButtonAction">
        <a href="#" class="dcf-btn dcf-btn-tertiary checkall">Check All</a>
        <a href="#" class="dcf-btn dcf-btn-tertiary uncheckall">Uncheck All</a>
    </div>
    <div class="dcf-input-group">
        <label for="storyaction" class="dcf-sr-only">Action to perform on checked items</label>
        <select class="dcf-input-select dcf-mb-0" style="width:auto" name="storyaction" onfocus="manager.list = '<?php echo $status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)" aria-label="Select bulk story action">
            <option>Select action...</option>
            <?php if ($parent->context->options['model'] === 'UNL_ENews_Manager') : ?>
                <option value="approved"  disabled="disabled">Add to Approved</option>
                <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
                <option value="recommend" disabled="disabled">Recommend</option>
            <?php endif ?>
            <option value="delete" disabled="disabled">Delete</option>
        </select>
    </div>
</div>
<table class="dcf-table dcf-table-bordered dcf-w-100% storylisting functionTable" >
    <thead>
        <tr>
            <?php
            $sortURL = '?view=mynews';
            if (isset($parent->context->options['newsroom'])) {
                $sortURL = '?view=manager&newsroom=' . urlencode($parent->context->options['newsroom']) . '&status=' . urlencode($status);
            }
            if (isset($term)) {
	            $sortURL .= '&term=' . urlencode($term);
            }
            $sortURL .= '&orderby=starttime';
            ?>
            <th scope="col" class="select"></th>
            <th scope="col" class="headline">Article</th>
            <th scope="col" class="firstdate"><a href="<?php echo $sortURL; ?>">First Pub Date</a></th>
            <th scope="col" class="lastdate"><a href="<?php echo $sortURL; ?>">Last Pub Date</a></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($context as $item) : ?>
        <tr id="row<?php echo $item->id; ?>" class="<?php echo $item->presentation->type; ?>">
            <td><div class="dcf-input-checkbox">
                    <input type="checkbox" name="story_<?php echo $item->id; ?>" id="story_<?php echo $item->id; ?>"/>
                    <label for="story_<?php echo $item->id; ?>"><span class="dcf-sr-only">Check story for action</span></label>
            </div></td>
            <td class="mainCell">
            	<?php if ($file = $item->getThumbnail()) { echo '<img src="'.$file->getURL().'" style="max-height:55px;float:right;" alt="'.htmlentities($file->getRaw('name'), ENT_QUOTES, 'UTF-8').'" />'; } ?>
               <h5><?php echo $item->title; ?></h5>
            	<a href="<?php echo UNL_ENews_Controller::getURL(); ?>?view=submit&amp;id=<?php echo $item->id; ?>" class="dcf-btn dcf-btn-primary action edit">Edit</a>
            	<span>Submitted by <?php echo $item->uid_created; ?>.
            	<?php if (!empty($item->uid_modified)) { ?>
            		Edited by <?php echo $item->uid_modified;?>.
            	<?php }?>
            	</span>
            </td>
            <td><?php echo date('Y-m-d', strtotime($item->request_publish_start)); ?></td>
            <td><?php echo date('Y-m-d', strtotime($item->request_publish_end)); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
    <div class="storyAction">
        <div class="storyButtonAction">
            <a href="#" class="dcf-btn dcf-btn-tertiary checkall">Check All</a>
            <a href="#" class="dcf-btn dcf-btn-tertiary uncheckall">Uncheck All</a>
        </div>
        <div class="dcf-input-group">
            <label for="storyaction" class="dcf-sr-only">Action to perform on checked items</label>
            <select class="dcf-input-select dcf-mb-0" style="width:auto" name="storyaction" onfocus="manager.list = '<?php echo $status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)" aria-label="Select bulk story action">
                <option>Select action...</option>
				<?php if ($parent->context->options['model'] === 'UNL_ENews_Manager') : ?>
                    <option value="approved"  disabled="disabled">Add to Approved</option>
                    <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
                    <option value="recommend" disabled="disabled">Recommend</option>
				<?php endif ?>
                <option value="delete" disabled="disabled">Delete</option>
            </select>
        </div>
    </div>
    <input class="dcf-d-none" id="delete_story" type="submit" name="delete" onclick="return confirm('Are you sure?');" value="Delete" />
	<?php if ($status=='approved' || $status=='archived') { ?>
        <input class="dcf-d-none" id="moveto_pending" type="submit" name="pending" value="Move to Pending" />
	<?php } elseif ($status=='pending') { ?>
        <input class="dcf-d-none" id="moveto_approved" type="submit" name="approved" value="Add to Approved" />
	<?php } ?>
</form>
<?php
if (isset($context->options['limit']) && count($context) > $context->options['limit']) {
	$pager = new stdClass();
	$pager->total  = count($context);
	$pager->limit  = $context->options['limit'];
	$pager->offset = $context->options['offset'];
	$pager->url    = $context->getManageURL(array('status'=>$status));
	echo $savvy->render($pager, 'ENews/PaginationLinks.tpl.php');
}
?>
<?php else: ?>
<div>No news submissions found for <em><?php echo $term; ?></em></div>
<?php endif; ?>

<?php
if ($showSearch) {
	$savvy->loadScriptDeclaration(trim("
        var searchForm = document.getElementById('search-form');
        var searchFormNotice = document.getElementById('search-form-notice');
        searchForm.addEventListener('submit', function(event) {
            searchFormNotice.classList.add('dcf-d-none');
            var term = searchForm.elements['term'].value;
            if (term.length > 0 && term.length < 3) {
                WDN.initializePlugin('notice');
                searchFormNotice.classList.add('dcf-d-block');
                event.preventDefault();
            }
        });
    "));
}
?>

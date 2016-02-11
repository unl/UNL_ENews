<?php
$status = 'approved';
if (isset($parent->context->options['status'])) {
    $status = $parent->context->options['status'];
}
if ($parent->context->options['model'] === 'UNL_ENews_User_StoryList') {
    $status = 'none';
}

if (count($context) == 0) {
    echo '<div class="four_col">No Gnews is Good Gnews with Gary Gnu!</div>';
    return;
}

?>


<script src="<?php echo UNL_ENews_Controller::getURL();?>/js/manager.js" type="text/javascript"></script>
<script type="text/javascript">
    WDN.loadJS("/wdn/templates_3.1/scripts/plugins/ui/jQuery.ui.js");
    WDN.loadCSS("/wdn/templates_3.1/css/content/forms.css");

    WDN.jQuery(document).ready(function() {
        manager.initialize();
    });
</script>

<form id="enewsManage" name="enewsManage" class="energetic" method="post" action="<?php echo $context->getManageURL(); ?>">

<input type="hidden" name="_type" value="change_status" />

<?php if ($parent->context->options['model'] === 'UNL_ENews_Manager') : ?>
<input type="hidden" name="newsroom" value="<?php echo $parent->context->options['newsroom']; ?>" />
<input type="hidden" name="status" value="<?php echo $status; ?>" />
<?php endif ?>

<div class="storyAction">
    <div class="storyButtonAction">
        <a href="#" class="wdn-button checkall">Check All</a>
        <a href="#" class="wdn-button uncheckall">Uncheck All</a>
    </div>
    <fieldset class="storyFieldsetAction">
        <legend>Action</legend>
        <label for="storyaction">Action</label>
        <select name="storyaction" onfocus="manager.list = '<?php echo $status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
            <option>Select action...</option>
            <?php if ($parent->context->options['model'] === 'UNL_ENews_Manager') : ?>
                <option value="approved"  disabled="disabled">Add to Approved</option>
                <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
                <option value="recommend" disabled="disabled">Recommend</option>
            <?php endif ?>
            <option value="delete"    disabled="disabled">Delete</option>
        </select>
    </fieldset>
</div>
<table class="storylisting functionTable" >
    <thead>
        <tr>
            <?php
            $sortURL = '?view=mynews';
            if (isset($parent->context->options['newsroom'])) {
                $sortURL = '?view=manager&amp;newsroom='.$parent->context->options['newsroom'].'&amp;status='.$status;
            }
            $sortURL .= '&amp;orderby=starttime';
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
            <td><input type="checkbox" name="story_<?php echo $item->id; ?>" /></td>
            <td class="mainCell">
            	<?php if ($file = $item->getThumbnail()) { echo '<img src="'.$file->getURL().'" style="max-height:55px;float:right;" alt="'.htmlentities($file->getRaw('name'), ENT_QUOTES, 'UTF-8').'" />'; } ?>
            	<h5 class="wdn-brand"><?php echo $item->title; ?></h5>
            	<a href="<?php echo UNL_ENews_Controller::getURL(); ?>?view=submit&amp;id=<?php echo $item->id; ?>" class="wdn-button wdn-button-triad action edit">Edit</a>
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
        <a href="#" class="wdn-button checkall">Check All</a>
        <a href="#" class="wdn-button uncheckall">Uncheck All</a>
    </div>
    <fieldset class="storyFieldsetAction">
        <legend>Action</legend>
        <label for="storyaction">Action</label>
        <select name="storyaction" onfocus="manager.list = '<?php echo $status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
            <option>Select action...</option>
            <?php if ($parent->context->options['model'] === 'UNL_ENews_Manager') : ?>
                <option value="approved"  disabled="disabled">Add to Approved</option>
                <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
                <option value="recommend" disabled="disabled">Recommend</option>
            <?php endif ?>
            <option value="delete"    disabled="disabled">Delete</option>
        </select>
    </fieldset>
</div>
<input class="btnsubmit" id="delete_story" type="submit" name="delete" onclick="return confirm('Are you sure?');" value="Delete" />
<?php if ($status=='approved' || $status=='archived') { ?>
<input class="btnsubmit" id="moveto_pending" type="submit" name="pending" value="Move to Pending" />
<?php } elseif ($status=='pending') { ?>
<input class="btnsubmit" id="moveto_approved" type="submit" name="approved" value="Add to Approved" />
<?php } ?>
</form>
<?php
if (isset($context->options['limit'])
    && count($context) > $context->options['limit']) {
    $pager = new stdClass();
    $pager->total  = count($context);
    $pager->limit  = $context->options['limit'];
    $pager->offset = $context->options['offset'];
    $pager->url    = $context->getManageURL(array('status'=>$status));
    echo $savvy->render($pager, 'ENews/PaginationLinks.tpl.php');
}
?>

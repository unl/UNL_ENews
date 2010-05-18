<?php
$status = 'approved';

if (isset($parent->context->options['status'])) {
    $status = $parent->context->options['status'];
}

?>


<script type="text/javascript">
WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
</script>

<form id="enewsManage" name="enewsManage" class="energetic" method="post" action="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $status; ?>">
<input type="hidden" name="_type" value="change_status" />
<div class="storyAction">
    <div class="storyButtonAction">
        <a href="#" class="checkall">Check All</a>
        <a href="#" class="uncheckall">Uncheck All</a>
    </div>
    <fieldset class="storyFieldsetAction">
        <legend>Action</legend>
        <label for="storyaction">Action</label> 
        <select name="storyaction" onfocus="manager.list = '<?php echo $status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
            <option>Select action...</option>
            <option value="approved"  disabled="disabled">Add to Approved</option>
            <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
            <option value="recommend" disabled="disabled">Recommend</option>
            <option value="delete"    disabled="disabled">Delete</option>
        </select>
    </fieldset>
</div>
<table class="storylisting zentable energetic" >
    <thead>
        <tr>
            <th scope="col" class="select">Select</th>
            <th scope="col" class="image">Image</th>
            <th scope="col" class="title"><a href="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $status; ?>&amp;orderby=title">Headline</a></th>
            <th scope="col" class="firstdate"><a href="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $status; ?>&amp;orderby=starttime">First Pub Date</a></th>
            <th scope="col" class="lastdate"><a href="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $status; ?>&amp;orderby=starttime">Last Pub Date</a></th>
            <th scope="col" class="submitter"><a href="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $status; ?>&amp;orderby=uid_created">Submitter</a></th>
            <th scope="col" class="modifier"><a href="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $status; ?>&amp;orderby=uid_modified">Last Edited By</a></th>
            <th scope="col" class="edit">Edit</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($context as $item) : ?>
        <tr id="row<?php echo $item->id; ?>">
            <td><input type="checkbox" name="story_<?php echo $item->id; ?>" /></td>
            <td><?php if ($file = $item->getThumbnail()) { echo '<img src="?view=file&amp;id='.$file->id.'" style="max-width:30px" alt="'.htmlentities($file->getRaw('name'), ENT_QUOTES).'" />'; } ?></td>
            <td><?php echo $item->title; ?></td>
            <td><?php echo date('Y-m-d', strtotime($item->request_publish_start)); ?></td>
            <td><?php echo date('Y-m-d', strtotime($item->request_publish_end)); ?></td>
            <td><?php echo $item->uid_created; ?></td>
            <td><?php echo $item->uid_modified; ?></td>
            <td><a href="?view=submit&amp;id=<?php echo $item->id; ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="storyAction">
    <div class="storyButtonAction">
        <a href="#" class="checkall">Check All</a>
        <a href="#" class="uncheckall">Uncheck All</a>
    </div>
    <fieldset class="storyFieldsetAction">
        <legend>Action</legend>
        <label for="storyaction">Action</label> 
        <select name="storyaction" onfocus="manager.list = '<?php echo $status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
            <option>Select action...</option>
            <option value="approved"  disabled="disabled">Add to Approved</option>
            <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
            <option value="recommend" disabled="disabled">Recommend</option>
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
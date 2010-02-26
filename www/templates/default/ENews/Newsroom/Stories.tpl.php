
<script type="text/javascript">
WDN.loadCSS("/wdn/templates_3.0/css/content/forms.css");
</script>

<form id="formlist" name="formlist" class="energetic" method="post" action="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $parent->context->options['status']; ?>">
<input type="hidden" name="_type" value="change_status" />
<div class="storyAction">
    <div class="storyButtonAction">
        <a href="#" class="checkall">Check All</a>
        <a href="#" class="uncheckall">Uncheck All</a>
    </div>
    <fieldset class="storyFieldsetAction">
        <legend>Action</legend>
        <label for="storyaction">Action</label> 
        <select name="storyaction" onfocus="manager.list = '<?php echo $parent->context->options['status']; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
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
            <th scope="col" class="title"><a href="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $parent->context->options['status']; ?>&amp;orderby=title">Headline</a></th>
            <th scope="col" class="date"><a href="?view=manager&amp;newsroom=<?php echo $parent->context->options['newsroom']; ?>&amp;status=<?php echo $parent->context->options['status']; ?>&amp;orderby=starttime">Request Publish Date</a></th>
            <th scope="col" class="edit">Edit</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($context as $item) : ?>
        <tr id="row<?php echo $item->id; ?>">
            <td><input type="checkbox" name="story_<?php echo $item->id; ?>" /></td>
            <td><?php echo $item->title; ?></td>
            <td><?php echo date('Y-m-d', strtotime($item->request_publish_start)); ?></td>
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
        <select name="storyaction" onfocus="manager.list = '<?php echo $parent->context->options['status']; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
            <option>Select action...</option>
            <option value="approved"  disabled="disabled">Add to Approved</option>
            <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
            <option value="recommend" disabled="disabled">Recommend</option>
            <option value="delete"    disabled="disabled">Delete</option>
        </select>
    </fieldset>
</div>
<input class="btnsubmit" id="delete_story" type="submit" name="delete" onclick="return confirm('Are you sure?');" value="Delete" />
<?php if ($parent->context->options['status']=='approved' || $parent->context->options['status']=='archived') { ?>
<input class="btnsubmit" id="moveto_pending" type="submit" name="pending" value="Move to Pending" />
<?php } elseif ($parent->context->options['status']=='pending') { ?>
<input class="btnsubmit" id="moveto_approved" type="submit" name="approved" value="Add to Approved" />
<?php } ?>
</form>
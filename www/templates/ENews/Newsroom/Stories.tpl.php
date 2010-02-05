<form method="post" action="?view=manage&amp;type=<?php echo $parent->context->options['type']; ?>">
<div class="storyAction">
    <div class="storyButtonAction">
        <a href="#" class="checkall" onclick="setCheckboxes('formlist',true); return false">Check All</a>
        <a href="#" class="uncheckall" onclick="setCheckboxes('formlist',false); return false">Uncheck All</a>
    </div>
    <fieldset class="storyFieldsetAction">
        <legend>Action</legend>
        <label for="storyaction">Action</label> 
        <select name="storyaction" onfocus="manager.list = '<?php echo $parent->context->options['type']; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
            <option>Select action...</option>
            <option value="posted"    disabled="disabled">Add to Posted</option>
            <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
            <option value="recommend" disabled="disabled">Recommend</option>
            <option value="delete"    disabled="disabled">Delete</option>
        </select>
    </fieldset>
</div>
<table class="storylisting">
<thead>
<tr>
<th scope="col" class="select">Select</th>
<th scope="col" class="title"><a href="?view=manager&amp;type=<?php echo $parent->context->options['type']; ?>&amp;orderby=title">Headline</a></th>
<th scope="col" class="date"><a href="?view=manager&amp;type=<?php echo $parent->context->options['type']; ?>&amp;orderby=starttime">Date</a></th>
<th scope="col" class="edit">Edit</th>
</tr>
</thead>
<tbody>
<?php foreach ($context as $item) : ?>
    <tr id="row<?php echo $item->id; ?>">
        <td><input type="checkbox" name="story_<?php echo $item->id; ?>" /></td>
        <td><?php echo $item->title; ?></td>
        <td><?php echo $item->event_date; ?></td>
        <td><a href="?view=submit&amp;id=<?php echo $item->id; ?>">Edit</a></td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="storyAction">
    <div class="storyButtonAction">
        <a href="#" class="checkall" onclick="setCheckboxes('formlist',true); return false">Check All</a>
        <a href="#" class="uncheckall" onclick="setCheckboxes('formlist',false); return false">Uncheck All</a>
    </div>
    <fieldset class="storyFieldsetAction">
        <legend>Action</legend>
        <label for="storyaction">Action</label> 
        <select name="storyaction" onfocus="manager.list = '<?php echo $parent->context->options['type']; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
            <option>Select action...</option>
            <option value="posted"    disabled="disabled">Add to Posted</option>
            <option value="pending"   disabled="disabled">Move to Pending/Embargoed</option>
            <option value="recommend" disabled="disabled">Recommend</option>
            <option value="delete"    disabled="disabled">Delete</option>
        </select>
    </fieldset>
</div>
</form>
<form class="dcf-form dcf-form-controls-inline dcf-mb-6" name="newsroom_choose" method="get" action="">
    <input type="hidden" name="view" value="manager">
    <div class="dcf-input-group">
        <select name="newsroom" id="newsroom" class="dcf-input-select dcf-mb-0" aria-label="Choose your newsroom">
            <option selected="selected" value="<?php echo $parent->context->options['newsroom'] ?>">Choose your newsroom</option>
            <?php foreach ($context as $item): ?>
                <option value="<?php echo $item->id;?>"><?php echo $item->name;?></option>
                <?php endforeach; ?>
        </select></li>
        <button class="dcf-btn dcf-btn-primary" type="submit" name="submit" id="submit">Switch</button>
     </div>
</form>

<div class="clear"></div>

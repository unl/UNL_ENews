<form class="enews newsroom_choose" name="newsroom_choose" method="get" action="">
	<input type="hidden" name="view" value="manager">
	<fieldset>
		<ol>
			<li>
			<select name="newsroom" id="newsroom">
				<option selected="selected" value="<?php echo $parent->context->options['newsroom'] ?>">Choose your newsroom</option>
				<?php foreach ($context as $item): ?>
				<option value="<?php echo $item->id;?>"><?php echo $item->name;?></option>
				<?php endforeach; ?>
			</select></li>
			<li><input type="submit" value="Go" name="submit" id="submit"></li>
		</ol>
	</fieldset>
</form>

<div class="clear"></div>
 
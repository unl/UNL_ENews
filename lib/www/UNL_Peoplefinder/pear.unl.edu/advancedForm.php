<form method="get" id="form1" action="<?php echo htmlentities(str_replace('index.php', '', $_SERVER['PHP_SELF']), ENT_QUOTES); ?>">
	<label for="sn">Last Name: </label>
	<input type="text" name="sn" value="<?php echo htmlentities(@$_GET['sn'], ENT_QUOTES); ?>" id="sn" />
	<br />
	<label for="cn">First Name: </label>
	<input type="text" name="cn" value="<?php echo htmlentities(@$_GET['cn'], ENT_QUOTES); ?>" id="cn" />
	<?php if (isset($_GET['chooser'])) {
		echo '<input type="hidden" name="chooser" value="true" />';
	} ?>
	<input type="hidden" name="adv" value="y" />
	<br />
	<label for="eppa">Affiliation: </label>
	<select id="eppa" name="eppa">
		<option value="any" <?php if(@$_GET['eppa'] == 'any') echo "selected='selected'"; ?>>Any</option>
		<option value="fs" <?php if(@$_GET['eppa'] == 'fs') echo "selected='selected'"; ?>>Faculty/Staff</option>
		<option value="stu" <?php if(@$_GET['eppa'] == 'stu') echo "selected='selected'"; ?>>Student</option>
	</select>
	<input style="margin-bottom:-7px;" name="submitbutton" type="image" src="/ucomm/templatedependents/templatecss/images/go.gif" value="Submit" id="submitbutton" />
</form>
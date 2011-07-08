<?php
UNL_ENews_Controller::setReplacementData('pagetitle', $context->title);
?>
<?php
foreach ($context->getFiles() as $file) {
    if (preg_match('/^image/', $file->type) && $file->use_for == 'originalimage') {

        $description = $file->name;
        if (!empty($file->description)) {
            $description = $file->description;
        }

        echo '<figure class="half">'
        	 . '<img src="'.$file->getURL()
             . '" alt="'.$description.'" />';
	         if (!empty($file->description)) {
	             echo '<figcaption>'.$file->description.'</figcaption>';
	         }
	    echo '</figure>';
    }
}
?>

<?php $full = trim($context->full_article); ?>
<?php if (!empty($full)) : ?>
    <p>
    <?php echo nl2br(UNL_ENews_Controller::makeClickableLinks($context->full_article)); ?>
    </p>
<?php else : ?>
    <?php echo nl2br($context->description); ?>
<?php endif; ?>
<?php if ($context->website) : ?>
    <p>
    More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage"><?php echo $context->website; ?></a>
    </p>
<?php endif; ?>

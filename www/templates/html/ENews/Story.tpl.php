<?php
UNL_ENews_PostRunFilter::setReplacementData('pagetitle', $context->title);
?>
<?php
foreach ($context->getFiles() as $file) {
    if (preg_match('/^image/', $file->type) && $file->use_for == 'originalimage') {

        $description = $file->name;
        if (!empty($file->description)) {
            $description = $file->description;
        }

        echo '<figure class="half legacy-wdn-frame">'
             . '<img src="'.$file->getURL()
             . '" alt="'.$description.'" />';
             if (!empty($file->description)) {
                 echo '<figcaption>'.$file->description.'</figcaption>';
             }
        echo '</figure>';
    }
}
?>

<?php if (
    strtotime($context->request_publish_end) < strtotime('-1 year') ||
    strtotime($context->date_modified) < strtotime('-3 year') ||
    strtotime($context->date_submitted) < strtotime('-3 year')
): ?>
<div class="unl-bg-scarlet unl-cream dcf-rounded dcf-p-3 dcf-mb-3">
    <strong>Archived Story:</strong> This article is part of our newsletter archives. It has
    been preserved for reference, but the information may no longer be current.
</div>
<?php endif ?>

<?php $full = trim($context->full_article); ?>
<?php if (!empty($full)) : ?>
    <p>
    <?php echo $savvy->render($context, 'ENews/Story/field-full_article.tpl.php'); ?>
    </p>
<?php else : ?>
    <?php echo $savvy->render($context, 'ENews/Story/field-description.tpl.php'); ?>
<?php endif; ?>
<?php if ($context->website) : ?>
    <p>
    More details at: <a href="<?php echo $context->website; ?>" title="Go to the supporting webpage"><?php echo $context->website; ?></a>
    </p>
<?php endif; ?>

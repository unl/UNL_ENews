<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><?php echo $context->newsroom->name; ?></title>
        <link><?php echo UNL_ENews_Controller::getURL(); ?></link>
        <description>Latest news from <?php echo $context->newsroom->name; ?></description>
        <language>en-us</language>
        <generator>Magic</generator>
        <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
        <atom:link href="<?php echo $context->newsroom->getURL(); ?>/latest?format=xml" rel="self" type="application/rss+xml" />
		<?php foreach($context as $item) :?>
			<?php echo $savvy->render($item, 'ENews/Story.tpl.php'); ?>
		<?php endforeach; ?>

    </channel>
</rss>
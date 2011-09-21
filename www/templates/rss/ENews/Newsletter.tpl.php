<?php
/* @var $context UNL_ENews_Newsletter */
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
        <title>News from the <?php echo $context->newsroom->name; ?> newsletter</title>
        <link><?php echo $context->getURL(); ?></link>
        <description>Stories from the <?php echo $context->newsroom->name; ?> newsletter</description>
        <language>en-us</language>
        <generator>UNL_ENews</generator>
        <lastBuildDate><?php echo date('r', strtotime($context->release_date)); ?></lastBuildDate>
        <atom:link href="<?php echo $context->getURL(); ?>?format=rss" rel="self" type="application/rss+xml" />
        <?php foreach($context->getStories() as $item) :?>
            <?php echo $savvy->render($item, 'ENews/Story.tpl.php'); ?>
        <?php endforeach; ?>
    </channel>
</rss>


<rss version="2.0">
    <channel>
        <title><?php echo $context->newsroom->name; ?> News</title>
        <link><?php echo UNL_ENews_Controller::getURL(); ?></link>
        <description>Events for <?php echo $context->newsroom->name; ?></description>
        <language>en-us</language>
        <generator>Magic</generator>
        <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
    </channel>   
    <?php echo $savvy->render($context->actionable); ?>
</rss>
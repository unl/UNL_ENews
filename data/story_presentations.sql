INSERT INTO `story_presentations` (`id`, `type`, `isdefault`, `description`, `template`) VALUES
(1, 'news', 1, 'Thumbnail floated left', 'News/ThumbnailFloatedLeft.tpl.php'),
(2, 'news', 0, 'Thumbnail floated right', 'News/ThumbnailFloatedRight.tpl.php'),
(3, 'news', 0, 'Full-Width image above text', 'News/FullColImage.tpl.php'),
(4, 'event', 1, 'Thumbnail floated left', 'Event/ThumbnailFloatedLeft.tpl.php'),
(5, 'event', 0, 'Thumbnail floated right', 'Event/ThumbnailFloatedRight.tpl.php'),
(6, 'ad', 1, 'Full-Width image only', 'Ad/FullImage.tpl.php'),
-- 7 was reserved for an invalid ad presentation
(8, 'event', 0, 'Full-Width image above text', 'Event/FullColImage.tpl.php'),
(9, 'news', 0, 'Full-Text and Full-Width image above', 'News/FullTextFullColImage.tpl.php'),
(10, 'news', 0, 'Image only linked to Supporting Website (supply Summary text to describe image for screen readers)', 'News/FullWidthImage.tpl.php')
    ON DUPLICATE KEY UPDATE template=VALUES(template), type=VALUES(type), isdefault=VALUES(isdefault), description=VALUES(description);

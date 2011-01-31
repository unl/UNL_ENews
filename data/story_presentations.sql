INSERT INTO `story_presentations` (`id`, `type`, `isdefault`, `description`, `template`) VALUES
(1, 'news', 1, 'Thumbnail floated left', 'News/ThumbnailFloatedLeft.tpl.php'),
(2, 'news', 0, 'Thumbnail floated right', 'News/ThumbnailFloatedRight.tpl.php'),
(3, 'news', 0, 'Full-Width image above text', 'News/FullColImage.tpl.php'),
(4, 'event', 1, 'Thumbnail floated left', 'Event/ThumbnailFloatedLeft.tpl.php'),
(5, 'event', 0, 'Thumbnail floated right', 'Event/ThumbnailFloatedRight.tpl.php'),
(6, 'ad', 1, 'Full-Width image only', 'Ad/FullImage.tpl.php')
    ON DUPLICATE KEY UPDATE template=VALUES(template), type=VALUES(type), isdefault=VALUES(isdefault), description=VALUES(description);
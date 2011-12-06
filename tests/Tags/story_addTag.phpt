--TEST--
Add a tag to a story
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';
$controller = new UNL_ENews_Controller();

$mysql = $controller::getDB();
$mysql->query('DELETE FROM tags');
$mysql->query('DELETE FROM story_tags');
$mysql->query('DELETE FROM stories');
$mysql->query("INSERT INTO `stories` (`id`, `title`, `description`, `full_article`, `request_publish_start`, `request_publish_end`, `sponsor`, `website`, `presentation_id`, `uid_created`, `uid_modified`, `date_submitted`, `date_modified`) VALUES
(1, 'UNL Extension Program - (CMDC) Field Crop Scout Training', ' The training is designed for entry level scouts who will be working for crop consultants, industry agronomists or farm service centers across Nebraska and neighboring states, said Keith Glewen, UNL Extension educator.\n\nThe course is from 9 a.m.-5 p.m. with registration at 8:30 a.m. May 11, 2010.', ' ', '2010-04-30 00:00:00', '2012-05-11 00:00:00', 'IANR', NULL, 1, 'erasmussen2', NULL, '2010-04-20 11:37:23', NULL);");

$tag = new UNL_ENews_Tag();
$tag->name = 'fish';
if (!$tag->save()) {
	echo 'Failed';
}

$story = UNL_ENews_Story::getByID(1);
$story->addTag($tag);

foreach ($story->getTags() as $tag) {
	echo $tag.PHP_EOL;
}

?>
===DONE===
--EXPECT--
fish
===DONE===
<?php
// I had to do this once, so here's how it was done:
// @TODO write a script that will change the story ID

?>

update stories set id=2078 where id=2119;
update newsletter_stories set story_id = 2078 where story_id=2119;
update story_files set story_id = 2078 where story_id=2119;
update newsroom_stories set story_id=2078 where story_id=2119;
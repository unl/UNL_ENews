<?php
class UNL_ENews_Story_Tags extends UNL_ENews_TagList
{
    function getSQL()
    {
    	return 'SELECT tag_id FROM story_tags WHERE story_id = '.(int)$this->options['story_id'];
    }
}
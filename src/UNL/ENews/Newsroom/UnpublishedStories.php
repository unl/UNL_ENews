<?php
class UNL_ENews_Newsroom_UnpublishedStories extends UNL_ENews_StoryList
{
    public $options = array('offset' => 0,
                            'limit'  => 30);

    function __construct($options = array())
    {
        $this->options = $options + $this->options;

        if (!isset($this->options['newsroom_id'])) {
            $this->options['newsroom_id'] = UNL_ENews_Controller::getUser(true)->newsroom->id;
        }

        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsroom_stories.story_id FROM newsroom_stories, stories 
                WHERE newsroom_stories.newsroom_id = '.(int)$this->options['newsroom_id'] . '
                  AND newsroom_stories.story_id = stories.id
                  AND newsroom_stories.story_id NOT IN
                    (
                    SELECT newsletter_stories.story_id FROM newsletter_stories, newsletters
                        WHERE newsletters.newsroom_id = '.(int)$this->options['newsroom_id']. '
                            AND newsletter_stories.newsletter_id = newsletters.id
                    )';
        if (!empty($this->options['status'])) {
            $sql .= ' AND newsroom_stories.status = \''.$mysqli->escape_string($this->options['status']).'\'';
        }
        if (!empty($this->options['date'])) {
            $sql .= ' AND \''.$mysqli->escape_string($this->options['date']).'\' BETWEEN stories.request_publish_start AND stories.request_publish_end';
        }
        $sql .= ' ORDER BY stories.title;';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        parent::__construct($stories, $this->options['offset'], $this->options['limit']);
    }
}
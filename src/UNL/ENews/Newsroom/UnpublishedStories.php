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
        $sql = 'SELECT nrs.story_id FROM newsroom_stories nrs
                JOIN stories s ON nrs.story_id = s.id AND nrs.newsroom_id = '.(int)$this->options['newsroom_id'].'
                LEFT JOIN newsletter_stories nls ON s.id = nls.story_id
                WHERE nls.newsletter_id IS NULL';

        if (!empty($this->options['status'])) {
            $sql .= ' AND nrs.status = \''.$mysqli->escape_string($this->options['status']).'\'';
        }
        if (!empty($this->options['date'])) {
            $sql .= ' AND \''.$mysqli->escape_string($this->options['date']).'\' BETWEEN s.request_publish_start AND s.request_publish_end';
        }
        $sql .= ' ORDER BY s.title;';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories, $this->options['offset'], $this->options['limit']);
    }
}
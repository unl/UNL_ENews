<?php
class UNL_ENews_Newsroom_UnpublishedStories extends UNL_ENews_StoryList
{
    public $options = array('offset' => 0,
                            'limit'  => 30);
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsroom_stories.story_id FROM newsroom_stories, stories 
                WHERE newsroom_stories.newsroom_id = '.(int)$options['newsroom_id'] . '
                  AND newsroom_stories.status = \'approved\'
                  AND newsroom_stories.story_id = stories.id
                  AND newsroom_stories.story_id NOT IN
                    (
                    SELECT newsletter_stories.story_id FROM newsletter_stories, newsletters
                        WHERE newsletters.newsroom_id = '.(int)$options['newsroom_id']. '
                            AND newsletter_stories.newsletter_id = newsletters.id
                    )
                    ORDER BY stories.title;';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories, $this->options['offset'], $this->options['limit']);
    }
}
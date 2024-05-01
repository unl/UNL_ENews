<?php
class UNL_ENews_StoryList_Submissions extends UNL_ENews_StoryList
{
    /**
     * The newsroom which has the stories
     * 
     * @var UNL_ENews_Newsroom
     */
    public $newsroom;

    public $options = array('offset' => 0,
                            'limit'  => -1);

    function __construct($options = array())
    {
        if (!$this->newsroom = UNL_ENews_Newsroom::getByOptions($options)) {
            throw new Exception('Newsroom not found', 404);
        }
        // This is only used for Nebraska Today submissions for importing into news.unl.edu.
        if ((int)$this->newsroom->id !== 1) {
            throw new Exception('This feed not available for this newsroom', 404);
        }
        $this->options = $options + $this->options;

        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = '
        	SELECT 
        		newsroom_stories.story_id 
        	FROM newsroom_stories, stories
        	WHERE
        		newsroom_stories.story_id = stories.id 
        			AND newsroom_stories.newsroom_id = '.(int)$this->newsroom->id. '
        			AND "'.date('Y-m-d H:i:s').'" <= stories.request_publish_end
        	ORDER BY stories.request_publish_start DESC';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        parent::__construct($stories, (int)$this->options['offset'], (int)$this->options['limit']);
    }
    
}

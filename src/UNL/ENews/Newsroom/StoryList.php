<?php
abstract class UNL_ENews_Newsroom_StoryList extends UNL_ENews_StoryList
{
    public $options = array('offset' => 0,
                            'limit'  => 30);

    /**
     * Newsroom associated with these stories
     *
     * @var UNL_ENews_Newsroom
     */
    protected $newsroom;

    function __construct($options = array())
    {
        if (!$this->newsroom = UNL_ENews_Newsroom::getByOptions($options)) {
            throw new Exception('Newsroom not found', 404);
        }

        $this->options = $options + $this->options;
        $stories = array();
        $sql = $this->getSQL();
        $mysqli = UNL_ENews_Controller::getDB();
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        parent::__construct($stories, (int)$this->options['offset'], (int)$this->options['limit']);
    }

    function getNewsroomSQL()
    {
        $sql = '
            SELECT newsroom_stories.story_id
            FROM newsroom_stories, stories
            WHERE newsroom_stories.newsroom_id = '.(int)$this->newsroom->id;
        return $sql;
    }

    abstract function getSQL();
}
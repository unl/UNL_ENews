<?php
class UNL_ENews_User_StoryList extends UNL_ENews_StoryList
{
    public $options = array(
        'uid'    => NULL,
        'limit'  => 30,
        'offset' => 0,
        'term' => ''
    );

    public $actionable = [];

    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        
        //Handle POST from ?view=mynews
        if (!empty($_POST)) {
            $manager = new UNL_ENews_Manager($this->options);
            $this->actionable = $manager->actionable;
        }
        
        if (!isset($this->options['uid'])) {
            $this->options['uid'] = UNL_ENews_Controller::getUser(true)->uid;
        }

        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT id FROM stories WHERE uid_created = ?';

        $termFiltered = false;
        if (!empty($this->options['term']) && strlen($this->options['term']) >= 3) {
            $termFiltered = true;
            $sql .= ' AND (stories.title like ? OR stories.description like ? OR stories.full_article like ?)';
        }
        $sql .= ' ORDER BY date_submitted DESC';

        $stmt = $mysqli->prepare($sql);
        if ($termFiltered) {
            $likeTerm = '%' . $this->options['term'] . '%';
            $stmt->bind_param('ssss', $this->options['uid'], $likeTerm, $likeTerm, $likeTerm);
        } else {
            $stmt->bind_param('s', $this->options['uid']);
        }
        $stories = array();
        $stmt->execute();
        if ($result = $stmt->get_result()) {
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        parent::__construct($stories, (int)$this->options['offset'], (int)$this->options['limit']);
    }

    public function getManageURL($additional_params = array())
    {
        $url = UNL_ENews_Controller::getURL().'?view=mynews';
        return UNL_ENews_Controller::addURLParams($url, $additional_params);
    }
}
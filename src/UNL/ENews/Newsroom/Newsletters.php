<?php
class UNL_ENews_Newsroom_Newsletters extends UNL_ENews_NewsletterList
{
    public $newsroom_id;

    public $options = array('offset' => 0,
                            'limit'  => 10);

    function __construct($options = array())
    {
        $this->options = $options + $this->options;

        if (!isset($options['newsroom_id'])) {
            $this->newsroom_id = UNL_ENews_Controller::getUser(true)->newsroom->id;
        } else {
            $this->newsroom_id = $options['newsroom_id'];
        }
        $letters = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT id FROM newsletters ';
        $sql .= 'WHERE newsroom_id = '. intval($this->newsroom_id) .
                ' ORDER BY release_date DESC';
        if (isset($options['limit'])) {
            $sql .= ' LIMIT ';
            if (isset($options['offset'])) {
                $sql .= (int)$options['offset'].',';
            }
            $sql .= (int)$options['limit'];
        }
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $letters[] = $row[0];
            }
        }
        parent::__construct($letters);
    }

    function count()
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT COUNT(id) FROM newsletters WHERE newsroom_id = '.(int)$this->newsroom_id;
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                return $row[0];
            }
        }
    }
}
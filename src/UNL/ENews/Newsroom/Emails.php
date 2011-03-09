<?php
class UNL_ENews_Newsroom_Emails extends ArrayIterator implements Countable
{
    function __construct($options = array())
    {
        if (!isset($options['newsroom_id'])) {
            throw new Exception('Whoah, what newsroom do you expect me to use here?');
        }
        $emails = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT id FROM newsroom_emails ';
        $sql .= ' WHERE newsroom_id = '.(int)$options['newsroom_id'];
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $emails[] = $row[0];
            }
        }
        parent::__construct($emails);
    }
    
    /**
     * @return UNL_ENews_Story
     */
    function current()
    {
        return UNL_ENews_Newsroom_Email::getByID(parent::current());
    }
    
}
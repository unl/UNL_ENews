<?php
class UNL_ENews_Latest extends ArrayIterator
{
    function __construct($options = array())
    {
        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT id FROM stories;';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories);
    }
    
    function current()
    {
        return UNL_ENews_Story::getByID(parent::current());
    }
    
}
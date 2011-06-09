<?php
class UNL_ENews_Story_Newsletters extends UNL_ENews_NewsletterList
{
    function __construct($options = array())
    {
        $newsletters = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsletter_id FROM newsletter_stories WHERE story_id = '.(int)$options['id'];
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $newsletters[] = $row[0];
            }
        }
        parent::__construct($newsletters);
    }
}
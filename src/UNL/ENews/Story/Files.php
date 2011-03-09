<?php
class UNL_ENews_Story_Files extends UNL_ENews_FileList
{
    function __construct($options = array())
    {
        $files = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT DISTINCT file_id FROM story_files ';
        $sql .= 'WHERE story_id = '.(int)$options['story_id'];
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $files[] = $row[0];
            }
        }
        parent::__construct($files);
    }
}
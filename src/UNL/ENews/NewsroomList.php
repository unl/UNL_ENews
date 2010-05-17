<?php
class UNL_ENews_NewsroomList extends ArrayIterator
{
    /**
     * @return UNL_ENews_Newsroom
     */
    function current()
    {
        return UNL_ENews_Newsroom::getByID(parent::current());
    }

    function allowSubmissions()
    {
        $newsroom_ids = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsrooms WHERE allow_submissions = '1'";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $newsroom_ids[] = UNL_ENews_Newsroom::getByID($row['id']);
            }
        }
        $mysqli->close();
        return $newsroom_ids;
    }
}
<?php
class UNL_ENews_PresentationLister extends UNL_ENews_LoginRequired
{
    /**
     * The newsletter
     *
     * @var UNL_ENews_PresentationList
     */
    public $presentation_list;

    function __postConstruct()
    {
        $presentations = array();
        $mysqli = UNL_ENews_Controller::getDB();

        $sql = 'SELECT id FROM story_presentations WHERE active = TRUE';
        if (isset($this->options['type'])) {
            $sql .= " AND type ='" . $mysqli->escape_string($this->options['type']) . "'";
        }

        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $presentations[] = $row[0];
            }
        }

        $this->presentation_list = new UNL_ENews_PresentationList($presentations);
    }
}
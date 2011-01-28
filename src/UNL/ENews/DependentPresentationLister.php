<?php
class UNL_ENews_DependentPresentationLister extends UNL_ENews_LoginRequired
{
    /**
     * The list
     *
     * @var UNL_ENews_PresentationList
     */
    public $presentation_list;

    function __postConstruct()
    {
        $presentations = array();
        $mysqli = UNL_ENews_Controller::getDB();

        $sql = 'SELECT id FROM story_presentations WHERE dependent_selector IS NOT NULL';

        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $presentations[] = $row[0];
            }
        }
        $mysqli->close();

        $this->presentation_list = new UNL_ENews_PresentationList($presentations);
    }
}
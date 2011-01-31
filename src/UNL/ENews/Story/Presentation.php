<?php
class UNL_ENews_Story_Presentation extends UNL_ENews_Record
{
    public $id;

    public $type;

    public $default;

    public $description;

    public $template;

    /**
     * A flag to specify if this presentation can be used in the newsletter generator
     * @var boolean
     */
    public $active;

    public static function getDefault($type)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM story_presentations WHERE isdefault = '1' AND type = '".$mysqli->escape_string($type)."' LIMIT 1;";
        $result = $mysqli->query($sql);

        if (!$result
            || $result->num_rows != 1) {
            return false;
        }
        $object = new self();

        UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
        return $object;
    }

    public static function getByID($id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM story_presentations WHERE id = '".(int)$id."' LIMIT 1;";
        $result = $mysqli->query($sql);

        if ($result && $result->num_rows != 1) {
            return false;
        }
        $object = new self();

        UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
        return $object;
    }

    public static function getIDsByType($type)
    {
        $ids = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT id FROM story_presentations WHERE type = '".$mysqli->escape_string($type)."';";
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $ids[] = $row[0];
            }
        }
        $mysqli->close();
        return $ids;
    }

    public static function getTypes()
    {
        $types = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT DISTINCT type FROM story_presentations';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $types[] = $row[0];
            }
        }
        $mysqli->close();
        return $types;
    }

    public function getTable()
    {
        return 'story_presentations';
    }
}
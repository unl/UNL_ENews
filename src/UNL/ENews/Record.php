<?php
class UNL_ENews_Record
{
    function save()
    {
        $sql = 'INSERT INTO '.$this->getTable();
        $fields = get_object_vars($this);
        $sql .= '('.implode(',', array_keys($fields)).')';
        $sql .= ' VALUES ('.str_repeat('?,',count($fields)-1).'?)';
        
        $mysqli = UNL_ENews_Controller::getDB();

        $stmt = $mysqli->prepare($sql);
        $values = array();
        $values[] = $this->getTypeString(array_keys($fields)); 
        foreach ($fields as $key=>$value) {
            $values[] =& $this->$key;
        }

        call_user_func_array(array($stmt, 'bind_param'), $values);
        if ($stmt->execute() === false) {
            throw new Exception($stmt->error);
        }
        $mysqli->close();
    }
    
    function getTypeString($fields)
    {
        $types = '';
        foreach ($fields as $name) {
            switch($name) {
                case 'id':
                case 'story_id':
                case 'length':
                    $types .= 'i';
                    break;
                default:
                    $types .= 's';
                    break;
            }
        }
        return $types;
    }
    
    public static function getRecordByID($table, $id, $field = 'id')
    {
        $mysqli = UNL_UCARE_Controller::getDB();
        $sql = "SELECT * FROM $table WHERE $field = ".intval($id);
        if ($result = $mysqli->query($sql)) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    function toArray()
    {
        return get_object_vars($this);
    }
}
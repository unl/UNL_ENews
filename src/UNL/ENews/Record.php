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

        if (!$stmt = $mysqli->prepare($sql)) {
            echo $mysqli->error;
        }

        $values = array();
        $values[] = $this->getTypeString(array_keys($fields)); 
        foreach ($fields as $key=>$value) {
            $values[] =& $this->$key;
        }

        call_user_func_array(array($stmt, 'bind_param'), $values);
        if ($stmt->execute() === false) {
            throw new Exception($stmt->error);
        }
        
        if ($msqli->insert_id !== 0) {
            $this->id = $msqli->insert_id;
        }
        
        $mysqli->close();
        return true;
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
    
    function getDate($str)
    {
        if ($time = strtotime($str)) {
            return date('Y-m-d', $time);
        }

        if (strpos($str, '/') !== false) {
            list($month, $year) = explode('/', $str);
            return $this->getDate($year.'-'.$month.'-01');
        }
        // strtotime couldn't handle it
        return false;
    }
    
    public static function getRecordByID($table, $id, $field = 'id')
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM $table WHERE $field = ".intval($id);
        if ($result = $mysqli->query($sql)) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    function delete()
    {
        if (!empty($this->id)) {
            $mysqli = UNL_ENews_Controller::getDB();
            $sql = "DELETE FROM ".$this->getTable()." WHERE id = ".intval($this->id).' LIMIT 1;';
            if ($result = $mysqli->query($sql)) {
                return true;
            }
        }
        return false;
    }
    
    function toArray()
    {
        return get_object_vars($this);
    }
}
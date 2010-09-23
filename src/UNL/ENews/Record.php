<?php
class UNL_ENews_Record
{
    protected function prepareInsertSQL(&$sql)
    {
        $sql = 'INSERT INTO '.$this->getTable();
        $fields = get_object_vars($this);
        $sql .= '(`'.implode('`,`', array_keys($fields)).'`)';
        $sql .= ' VALUES ('.str_repeat('?,',count($fields)-1).'?)';
        return $fields;
    }
    
    function prepareUpdateSQL(&$sql)
    {
        $sql = 'UPDATE '.$this->getTable().' ';
        $fields = get_object_vars($this);

        $sql .= 'SET `'.implode('`=?,`', array_keys($fields)).'`=? ';

        $sql .= 'WHERE ';
        foreach ($this->keys() as $key) {
            $sql .= $key.'=? AND ';
        }

        $sql = substr($sql, 0, -4);

        return $fields;
    }
    
    function keys()
    {
        return array(
            'id',
        );
    }
    
    function save()
    {
        $key_set = true;

        foreach ($this->keys() as $key) {
            if (empty($this->$key)) {
                $key_set = false;
            }
        }

        $sql = '';

        if (!$key_set) {
            $fields = $this->prepareInsertSQL($sql);
        } else {
            $fields = $this->prepareUpdateSQL($sql);
        }

        $values = array();
        $values[] = $this->getTypeString(array_keys($fields)); 
        foreach ($fields as $key=>$value) {
            $values[] =& $this->$key;
        }
        
        if ($key_set) {
            // We're doing an update, so add in the keys!
            $values[0] .= $this->getTypeString($this->keys());
            foreach ($this->keys() as $key) {
                $values[] =& $this->$key;
            }
        }
        return $this->prepareAndExecute($sql, $values);
    }
    
    function insert()
    {
        $sql = '';
        $fields = $this->prepareInsertSQL($sql);
        $values = array();
        $values[] = $this->getTypeString(array_keys($fields));
        foreach ($fields as $key=>$value) {
            $values[] =& $this->$key;
        }
        return $this->prepareAndExecute($sql, $values);
    }
    
    function update()
    {
        $sql = '';
        $fields = $this->prepareUpdateSQL($sql);
        $values = array();
        $values[] = $this->getTypeString(array_keys($fields));
        foreach ($fields as $key=>$value) {
            $values[] =& $this->$key;
        }
        // We're doing an update, so add in the keys!
        $values[0] .= $this->getTypeString($this->keys());
        foreach ($this->keys() as $key) {
            $values[] =& $this->$key;
        }
        return $this->prepareAndExecute($sql, $values);
    }

    protected function prepareAndExecute($sql, $values)
    {
        $mysqli = self::getDB();

        if (!$stmt = $mysqli->prepare($sql)) {
            echo $mysqli->error;
        }

        call_user_func_array(array($stmt, 'bind_param'), $values);
        if ($stmt->execute() === false) {
            throw new Exception($stmt->error, 500);
        }

        if ($mysqli->insert_id !== 0) {
            $this->id = $mysqli->insert_id;
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
                case 'newsroom_id':
                case 'file_id':
                case 'story_id':
                case 'length':
                case 'sort_order':
                case 'distributed':
                case 'allow_submissions':
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
            list($month, $day, $year) = explode('/', $str);
            return $this->getDate($year.'-'.$month.'-'.$day);
        }
        // strtotime couldn't handle it
        return false;
    }
    
    public static function getRecordByID($table, $id, $field = 'id')
    {
        $mysqli = self::getDB();
        $sql = "SELECT * FROM $table WHERE $field = ".intval($id).' LIMIT 1;';
        if ($result = $mysqli->query($sql)) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    function delete()
    {
        $mysqli = self::getDB();
        $sql = "DELETE FROM ".$this->getTable()." WHERE ";
        foreach ($this->keys() as $key) {
            if (empty($this->$key)) {
                throw new Exception('Cannot delete records with unset primary keys!', 400);
            }
            $value = $this->$key;
            if ($this->getTypeString(array($key)) == 's') {
                $value = '"'.$mysqli->escape_string($value).'"';
            }
            $sql .= $key.'='.$value.' AND ';
        }
        $sql = substr($sql, 0, -4);
        $sql .= ' LIMIT 1;';
        if ($result = $mysqli->query($sql)) {
            return true;
        }
        return false;
    }

    public static function __callStatic($method, $args)
    {
        switch (true) {
            case preg_match('/getBy([\w]+)/', $method, $matches):
                $mysqli = self::getDB();
                $class  = get_called_class();
                $record = new $class;
                $field  = strtolower($matches[1]);
                $sql    = 'SELECT * FROM '.$record->getTable(). ' WHERE '.$field.' = "'.$mysqli->escape_string($args[0]).'"';
                $result = $mysqli->query($sql);

                if ($result === false
                    || $result->num_rows == 0) {
                    return false;
                }

                UNL_ENews_Controller::setObjectFromArray($record, $result->fetch_assoc());
                return $record;
        }
        throw new Exception('Invalid static method called.');
    }

    /**
     * Get the DB
     * 
     * @return mysqli
     */
    public static function getDB()
    {
        return UNL_ENews_Controller::getDB();
    }

    function toArray()
    {
        return get_object_vars($this);
    }
}
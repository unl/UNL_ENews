<?php
class UNL_ENews_NewsletterList extends ArrayIterator
{
    
    /**
     * @return UNL_ENews_Newsletter
     */
    function current()
    {
        return UNL_ENews_Newsletter::getByID(parent::current());
    }
    
    public static function getUndistributed()
    {
        $letters = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT id FROM newsletters '
             . 'WHERE '
             . '  distributed = 0 '                        // The newsletter has not been distributed
             . '  AND release_date IS NOT NULL '           // They have a release date set
             . '  AND release_date < '.date('Y-m-d H:i:s') // They should have been sent
             . ' ORDER BY release_date ASC;';              // Make sure we send them in the correct order
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $letters[] = $row[0];
            }
        }
        $mysqli->close();
        return new self($letters);
    }
}
<?php
class UNL_ENews_Newsletter_Open extends UNL_ENews_Record
{
    public $ip;

    public $newsletter_id;

    /**
     * @property $newsletter UNL_ENews_Newsletter
     */

    function __construct($options = array())
    {
        if (isset($options['id'])) {
            // Open the newsletter requested
            $this->newsletter = UNL_ENews_Newsletter::getById($options['id']);
            if (!$this->newsletter) {
                throw new Exception('Could not find that newsletter', 404);
            }

            $this->ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

            $this->newsletter_id = $options['id'];

            if (!$this->recordExists() && $this->newsletter->distributed) {
                $this->newsletter->opens++;
                $this->newsletter->save();

                unset($this->newsletter);
                $this->insert();
            }
        }
    }

    function recordExists()
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsletter_open WHERE newsletter_id = ".(int)$this->newsletter_id." AND ip = '".$mysqli->escape_string($this->ip)."'";
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            $object->synchronizeWithArray($result->fetch_assoc());
            return $object;
        }
        return false;
    }

    function getTable()
    {
        return 'newsletter_open';
    }

    function keys()
    {
        return array('ip', 'newsletter_id');
    }
}

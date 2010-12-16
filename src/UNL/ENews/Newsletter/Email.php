<?php
class UNL_ENews_Newsletter_Email extends UNL_ENews_Record
{
    public $newsletter_id;
    public $newsroom_email_id;

    static function getById($newsletter_id, $newsroom_email_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsletter_emails WHERE newsletter_id = ".(int)$newsletter_id." AND newsroom_email_id = ".(int)$newsroom_email_id;
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
            return $object;
        }
        return false;
    }

    function getTable()
    {
        return 'newsletter_emails';
    }

    function keys()
    {
        return array('newsletter_id', 'newsroom_email_id');
    }
}
<?php
class UNL_ENews_Newsletter_Emails extends ArrayIterator implements Countable
{
    function __construct($options = array())
    {
        if (!isset($options['newsletter_id'])) {
            throw new Exception('Whoah, what newsletter do you expect me to use here?');
        }
        $emails = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsletter_emails.newsroom_email_id
                FROM newsletter_emails, newsroom_emails
                WHERE newsletter_emails.newsletter_id = '.(int)$options['newsletter_id'].'
                    AND newsletter_emails.newsroom_email_id = newsroom_emails.id;';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $emails[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($emails);
    }
    
    /**
     * @return UNL_ENews_Story
     */
    function current()
    {
        return UNL_ENews_Newsroom_Email::getByID(parent::current());
    }
}
<?php
class UNL_ENews_Newsletter extends UNL_ENews_Record
{
    /**
     * Unique ID for this newsletter
     * 
     * @var int
     */
    public $id;
    
    /**
     * The newsroom this letter is associated with
     * 
     * @var int
     */
    public $newsroom_id;
    
    /**
     * Date this release will be published
     * 
     * @var string Y-m-d H:i:s
     */
    public $release_date;
    
    /**
     * Subject for the email for this newsletter
     * 
     * @var string
     */
    public $subject;
    
    /**
     * Optional introductory text to the email, prepended to the list of stories
     * 
     * @var string
     */
    public $intro;
    
    /**
     * Whether this newsletter has been distributed
     * 
     * @var int
     */
    public $distributed = 0;
    
    function __construct($options = array())
    {
        
    }
    
    /**
     * Get a newsletter by its primary key
     * 
     * @param int $id
     * 
     * @return UNL_ENews_Newsletter
     */
    public static function getByID($id)
    {
        if ($record = UNL_ENews_Record::getRecordByID('newsletters', $id)) {
            $object = new self();
            $object->synchronizeWithArray($record);
            return $object;
        }
        return false;
    }

    /**
     * Get the last-modified newsletter
     * 
     * @return UNL_ENews_Newsletter
     */
    public static function getLastModified()
    {
        $object = new self();
        $sql = "SELECT * FROM newsletters WHERE newsroom_id = ".intval(UNL_ENews_Controller::getUser(true)->newsroom->id)." AND release_date IS NULL";
        $mysqli = UNL_ENews_Controller::getDB();
        if (($result = $mysqli->query($sql))
            && $result->num_rows == 1) {
            $object->synchronizeWithArray($result->fetch_assoc());
            return $object;
        }
        
        // None there yet, let's start up a new one
        $object->newsroom_id = UNL_ENews_Controller::getUser(true)->newsroom->id;
        $object->subject     = UNL_ENews_Controller::getUser(true)->newsroom->name;
        if (isset($_GET['date'])) {
            $object->release_date = date('Y-m-d H:i:s', strtotime($_GET['date']));
        }
        $object->save();
        
        return $object;
    }

    /**
     * Get the most recent newsletter
     * 
     * @param int $newsroomID Optional newsroom id to get newsletter from
     * 
     * @return UNL_ENews_Newsletter
     */
    public static function getLastReleased($newsroomID)
    {
        $object = new self();
        if ($newsroomID != NULL) {
            $sql = "SELECT * FROM newsletters WHERE distributed = '1' AND newsroom_id = '".(int)$newsroomID."' ORDER BY release_date DESC LIMIT 1";
        } else {
            $sql = "SELECT * FROM newsletters WHERE distributed = '1' ORDER BY release_date DESC LIMIT 1";
        }
        $mysqli = UNL_ENews_Controller::getDB();
        $result = $mysqli->query($sql);

        if ($result->num_rows != 1) {
            return false;
        }

        $object->synchronizeWithArray($result->fetch_assoc());
        return $object;
    }

    function save()
    {
        if (!empty($this->release_date)) {
            $this->release_date = $this->getDate($this->release_date);
        }
        return parent::save();
    }

    function insert()
    {
        $result = parent::insert();
        if (!$result) {
            return $result;
        }

        // Add all the default email addresses
        foreach (new UNL_ENews_Newsroom_Emails_Filter_ByDefault($this->getNewsroom()->getEmails()) as $email) {
            $this->addEmail($email);
        }

        return $result;
    }

    /**
     * Add email distribution target for this newsletter
     * 
     * @param UNL_ENews_Newsroom_Email $email
     * 
     * @return boolean
     */
    function addEmail(UNL_ENews_Newsroom_Email $email)
    {
        $existing = $this->getEmails();
        foreach ($existing as $existing_email) {
            if ($existing_email->id == $email->id) {
                // we already have this email address
                return true;
            }
        }
        $newsletter_email = new UNL_ENews_Newsletter_Email();
        $newsletter_email->newsletter_id = $this->id;
        $newsletter_email->newsroom_email_id = $email->id;
        return $newsletter_email->insert();
    }

    /**
     * Remove an email distribution target for this newsletter
     * 
     * @param UNL_ENews_Newsletter_Email $email
     * @throws Exception
     * 
     * @return boolean
     */
    function removeEmail(UNL_ENews_Newsletter_Email $email)
    {
        if ($email->newsletter_id != $this->id) {
            throw new Exception('That email doesn\'t belong to you. Take off, eh!', 403);
        }
        return $email->delete();
    }

    function getEmails($options = array())
    {
        $options += array('newsletter_id'=>$this->id);
        return new UNL_ENews_Newsletter_Emails($options);
    }

    function delete()
    {
        foreach($this->getStories() as $has_story) {
            // Remove the link between this newsletter and the story story
            $has_story->delete();
        }
        return parent::delete();
    }
    
    function getTable()
    {
        return 'newsletters';
    }

    /**
     * Add a story to this newsletter
     * 
     * @param UNL_ENews_Story $story
     * @param int             $sort_order
     * @param string          $intro
     * 
     * @return boolean
     */
    function addStory(UNL_ENews_Story $story, $sort_order = null, $intro = null)
    {
        if ($has_story = UNL_ENews_Newsletter_Story::getById($this->id, $story->id)) {
            $has_story->sort_order = $sort_order;
            
            if ($intro) {
                $has_story->intro = $intro;
            }
            
            $has_story->update();
            
            // Already have this story thanks
            return true;
        }
        
        $has_story = new UNL_ENews_Newsletter_Story();
        $has_story->newsletter_id = $this->id;
        $has_story->story_id      = $story->id;
        $has_story->sort_order    = $sort_order;
        
        if ($intro) {
            $has_story->intro = $intro;
        }
        return $has_story->insert();
    }

    /**
     * Check if newsletter has a story
     * 
     * @param UNL_ENews_Story $story
     * 
     * @return boolean
     */
    function hasStory(UNL_ENews_Story $story)
    {
        if ($has_story = UNL_ENews_Newsletter_Story::getById($this->id, $story->id)) {
            return true;
        }
        return false;
    }

    /**
     * Remove a story from this newsletter
     * 
     * @param UNL_ENews_Story $story
     * 
     * @return boolean
     */
    function removeStory(UNL_ENews_Story $story)
    {
        if ($has_story = UNL_ENews_Newsletter_Story::getById($this->id, $story->id)) {

            return $has_story->delete();
        }

        return true;
    }

    /**
     * Get the stories associated with this newsletter
     * 
     * @return UNL_ENews_Newsletter_Stories
     */
    function getStories()
    {
        return new UNL_ENews_Newsletter_Stories(array('newsletter_id'=>$this->id));
    }
    
    function __get($var)
    {
        switch($var) {
            case 'newsroom':
                return $this->getNewsroom();
        }
        return false;
    }

    /**
     * Distribute this newsletter
     * 
     * @param string|array $addresses Email addresses to send to
     * @throws Exception
     * @return boolean
     */
    function distribute($addresses = null)
    {

        if (!isset($this->release_date)) {
            $this->release_date = date('Y-m-d H:i:s');
        }

        if (!isset($addresses)) {
            // No address was sent, let's get the Email addresses we need to send to
            $addresses = $this->getEmails();
        }

        if (gettype($addresses) == 'string') {

            // Fake an email, and email list object
            $address              = new UNL_ENews_Newsroom_Email();
            $address->email       = $addresses;
            $address->optout      = false;
            $address->newsroom_id = $this->newsroom_id;

            $addresses = array($address);
        }
        
        // Set a default from address
        $from = 'no-reply@newsroom.unl.edu';

        // Get the associated newsroom, and use the from address if necessary
        $newsroom = $this->getNewsroom();
        if (isset($newsroom->from_address)) {
            $from = $newsroom->from_address;
        }

        switch ($from) {
            case 'today@unl.edu':
                if (1 != $newsroom->id) {
                    throw new Exception('You are not authorized to send email from that address.', 403);
                }
                break;
            case 'nextnebraska@unl.edu':
                if (5 != $newsroom->id) {
                    throw new Exception('You are not authorized to send email from that address.', 403);
                }
                break;
            default:
                // must be fine
        }

        $html = array();
        $text = array();
        foreach ($addresses as $address) {
            if (!isset($html[$address->id], $text[$address->id])) {
                // We haven't built the html for this type of message, build it
                $html[$address->id] = $this->getEmailHTML($address);
                $text[$address->id] = $this->getEmailTXT($address);
            }

            // Send the email!
            $this->sendEmail(
                $from,
                $address->email,
                $this->subject,
                $html[$address->id],
                $text[$address->id]
            );
        }
        return true;
    }

    /**
     * Send email to specific email address
     * 
     * @param string $from     Email from: address
     * @param string $to       Email to: address
     * @param string $subject  Email subject
     * @param string $htmlBody HTML body of the email
     * @param string $txtBody  Text-only body of the email
     * 
     * @return boolean
     */
    function sendEmail($from, $to, $subject, $htmlBody, $txtBody)
    {
        // Load the mail library
        require_once 'Mail/mime.php';

        $hdrs = array(
          'From'    => $from,
          'Subject' => $subject);

        $mime = new Mail_mime("\n");
        $mime->setTXTBody($txtBody);
        $mime->setHTMLBody($htmlBody);

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);
        $mail =& Mail::factory('sendmail');

        // Send the email!
        $mail->send($to, $hdrs, $body);
        return true;
    }

    /**
     * Get the HTML email for this newsletter
     * 
     * @param UNL_ENews_Newsroom_Email $email The email to distribute to
     * 
     * @return string
     */
    function getEmailHTML(UNL_ENews_Newsroom_Email $email = null)
    {
        $savvy = $this->getEmailRenderer('email');
        $savvy->setEscape('htmlentities');

        $body = $savvy->render($this);

        if (isset($email) && $email->optout) {
            $optout_message = $savvy->render(new UNL_ENews_Newsletter_OptOut(array('email'=>$email)));
            if (strpos($body, '<!-- optout -->') !== false) {
                // The newsletter html has a placeholder for the optout message, insert it here
                $body = str_replace(
                            '<!-- optout -->', // placeholder text
                            $optout_message,   // rendered optout message
                            $body              // current body
                        );
            } else {
                // No placeholder found, just append the optout message
                $body .= $optout_message;
            }
        }

        $html = '<html>'.
                '<body style="word-wrap: break-word;" bgcolor="#ffffff">'.
                    $body .
                '</body>'.
                '</html>';
        return $html;
    }

    /**
     * Get the outputcontroller for rendering emails.
     * 
     * @param string $format The email format: email|text
     * 
     * @return UNL_ENews_OutputController
     */
    protected function getEmailRenderer($format)
    {
        $newsroom = $this->getNewsroom();
        $savvy = new UNL_ENews_OutputController();
        if (is_dir(dirname(dirname(dirname(__DIR__))) . '/www/themes/'.$newsroom->shortname)) {
            $savvy->setTheme($newsroom->shortname);
        }
        $savvy->setTemplateFormatPaths('html');
        $savvy->addTemplateFormatPaths($format);
        return $savvy;
    }

    /**
     * Get text-only email of this newsletter
     * 
     * @param UNL_ENews_Newsroom_Email $email The email to distribute to
     * 
     * @return string
     */
    function getEmailTXT(UNL_ENews_Newsroom_Email $email)
    {
        $savvy = $this->getEmailRenderer('text');
        $text = $savvy->render($this);

        if ($email->optout) {
            $text .= $savvy->render(new UNL_ENews_Newsletter_OptOut(array('email'=>$email)));
        }

        return $text;
    }

    /**
     * Get the newsroom associated with this newsletter.
     * 
     * @return UNL_ENews_Newsroom
     */
    function getNewsroom()
    {
        return UNL_ENews_Newsroom::getByID($this->newsroom_id);
    }

    /**
     * Get the URL to this newsletter
     * 
     * @return string
     */
    function getURL()
    {
        return $this->newsroom->getURL().'/'.$this->id;
    }

    /**
     * Get the URL for editing this newsletter
     *
     * @return string
     */
    function getEditURL()
    {
        return UNL_ENews_Controller::getURL().'?view=preview&id='.$this->id;
    }
}
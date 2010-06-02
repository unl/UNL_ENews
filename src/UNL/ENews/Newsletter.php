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
     * 
     * @param int $id
     * 
     * @return UNL_ENews_Newsletter
     */
    public static function getByID($id)
    {
        if ($record = UNL_ENews_Record::getRecordByID('newsletters', $id)) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $record);
            return $object;
        }
        return false;
    }
    
    public static function getLastModified()
    {
        $object = new self();
        $sql = "SELECT * FROM newsletters WHERE newsroom_id = ".intval(UNL_ENews_Controller::getUser(true)->newsroom->id)." AND release_date IS NULL";
        $mysqli = UNL_ENews_Controller::getDB();
        if (($result = $mysqli->query($sql))
            && $result->num_rows == 1) {
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
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
    
    public static function getLastReleased()
    {
        $object = new self();
        $sql = "SELECT * FROM newsletters WHERE distributed = '1' ORDER BY release_date DESC LIMIT 1";
        $mysqli = UNL_ENews_Controller::getDB();
        if (($result = $mysqli->query($sql))
            && $result->num_rows == 1) {
            UNL_ENews_Controller::setObjectFromArray($object, $result->fetch_assoc());
            return $object;
        }
        
        return $object;
    }
    
    function save()
    {
        if (!empty($this->release_date)) {
            $this->release_date = $this->getDate($this->release_date);
        }
        return parent::save();
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
    
    function hasStory(UNL_ENews_Story $story)
    {
        if ($has_story = UNL_ENews_Newsletter_Story::getById($this->id, $story->id)) {
            return true;
        }
        return false;
    }
    
    function removeStory(UNL_ENews_Story $story)
    {
        if ($has_story = UNL_ENews_Newsletter_Story::getById($this->id, $story->id)) {

            return $has_story->delete();
        }

        return true;
    }
    
    function getStories()
    {
        return new UNL_ENews_Newsletter_Stories(array('newsletter_id'=>$this->id));
    }
    
    function __get($var)
    {
        switch($var) {
            case 'newsroom':
                return UNL_ENews_Newsroom::getById($this->newsroom_id);
        }
        return false;
    }
    
    function distribute($to = null)
    {
        if (!isset($this->release_date)) {
            $this->release_date = date('Y-m-d H:i:s');
        }

        $plaintext = $this->getEmailTXT();
        $html      = $this->getEmailHTML();

        // @TODO THIS SHOULD BE NEWSLETTER SPECIFIC, and NOT configurable to be today@unl.edu
        $from = 'no-reply@newsroom.unl.edu';

        if ($this->newsroom_id == 1) {
            // Only set from address to today@unl.edu if the default newsroom.
            $from = 'today@unl.edu';
        }

        if (!isset($to)) {
            $to = $this->getNewsroom()->email_lists;
        }

        $hdrs = array(
          'From'    => $from,
          'Subject' => $this->subject);

        require_once 'Mail/mime.php';
        $mime = new Mail_mime("\n");
        $mime->setTXTBody($plaintext);
        $mime->setHTMLBody($html);

        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);
        $mail =& Mail::factory('sendmail');


        // Send the email!
        $mail->send($to, $hdrs, $body);
        return true;
    }
    
    function getEmailHTML()
    {
        Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_';

        $savvy = new Savvy();
        $savvy->setTemplatePath(dirname(dirname(dirname(dirname(__FILE__)))).'/www/templates/email');
        $savvy->setEscape('htmlentities');

        $html = "<html>".
                "<body bgcolor='#ffffff'>".
                    $savvy->render($this).
                "</body>".
            "</html>";
        return $html;
    }
    
    function getEmailTXT()
    {
        Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_';
        $savvy = new Savvy();
        $savvy->setTemplatePath(dirname(dirname(dirname(dirname(__FILE__)))).'/www/templates/text');
        
        return $savvy->render($this);
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
}
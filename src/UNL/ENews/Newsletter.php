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
        $object->subject = "My newsletter";
        if (isset($_GET['date'])) {
            $object->release_date = date('Y-m-d H:i:s', strtotime($_GET['date']));
        }
        $object->save();
        
        return $object;
    }
    
    public static function getLastReleased()
    {
        $object = new self();
        $sql = "SELECT * FROM newsletters WHERE release_date IS NOT NULL ORDER BY release_date DESC LIMIT 1";
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
            
        throw new Exception('Could not add the story');
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
    
    function distribute()
    {
        
        if (!isset($this->release_date)) {
            $this->release_date = date('Y-m-d H:i:s');
            $this->save();
        }
        
        Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_';
        $savvy = new Savvy();
        $savvy->setTemplatePath(dirname(dirname(dirname(dirname(__FILE__)))).'/www/templates/text');
        
        $plaintext = $savvy->render($this);

        $savvy = new Savvy();
        $savvy->setTemplatePath(dirname(dirname(dirname(dirname(__FILE__)))).'/www/templates/email');
        $savvy->setEscape('htmlentities');

        $html = "<html>".
                "<body bgcolor='#ffffff'>".
                    $savvy->render($this).
                "</body>".
            "</html>";
        $crlf = "\n";
        $hdrs = array(
          'From'    => 'today@unl.edu', // @TODO THIS NEEDS TO BE NEWSLETTER SPECIFIC, and NOT configurable to be today@unl.edu
          'Subject' => $this->subject);
        
        require_once 'Mail/mime.php';
        $mime = new Mail_mime($crlf);
        $mime->setTXTBody($plaintext);
        $mime->setHTMLBody($html);
        
        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);
        $mail =& Mail::factory('sendmail');
        $mail->send('ericrasmussen1@gmail.com', $hdrs, $body);
        $mail->send('brett.bieber@gmail.com', $hdrs, $body);
        $mail->send('today@listserv.unl.edu', $hdrs, $body); // @TODO THIS NEEDS TO BE NEWSLETTER SPECIFIC, and NOT configurable to today@unl.edu
        
        $this->distributed = 1;
        $this->save();
        // Send the email!
        return true;
    }
}
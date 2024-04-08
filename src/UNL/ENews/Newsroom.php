<?php
class UNL_ENews_Newsroom extends UNL_ENews_Record
{
    public $id;

    public $name;

    public $subtitle;

    public $shortname;

    public $website;

    public $allow_submissions;

    public $footer_text;

    public $email_lists;

    public $from_address;

    public $submit_url;

    public $private_web_view;

    function __construct($options = array())
    {

    }

    function getStories($status = 'pending', $term = '')
    {
        return new UNL_ENews_Newsroom_Stories(array('newsroom_id'=>$this->id, 'status' => $status, 'term' => $term));
    }

    function getNewsletters($options = array())
    {
        $options += array('newsroom_id'=>$this->id);
        return new UNL_ENews_Newsroom_Newsletters($options);
    }

    function getEmails($options = array())
    {
        $options += array('newsroom_id'=>$this->id);
        return new UNL_ENews_Newsroom_Emails($options);
    }

    /**
     * Get the emails that are opt-out
     * 
     * @return UNL_ENews_Newsroom_Emails_Filter_ByOptOut
     */
    function getOptOutEmails()
    {
        return new UNL_ENews_Newsroom_Emails_Filter_ByOptOut($this->getEmails());
    }

    function addEmail($email_address, $optout = 0, $newsletter_default = 1)
    {
        $existing = $this->getEmails();
        foreach ($existing as $existing_email) {
            if ($existing_email->email == $email_address) {
                // we already have this email address
                return;
            }
        }
        $email = new UNL_ENews_Newsroom_Email();
        $email->email = $email_address;
        $email->newsroom_id = $this->id;
        $email->newsletter_default = $newsletter_default;
        $email->optout = $optout;
        return $email->insert();
    }

    function removeEmail(UNL_ENews_Newsroom_Email $email)
    {
        if ($email->newsroom_id != $this->id) {
            throw new Exception('That email doesn\'t belong to you. Take off, eh!', 403);
        }
        return $email->delete();
    }

    function getTable()
    {
        return 'newsrooms';
    }

    function getUsers()
    {
        return new UNL_ENews_Newsroom_Users(array('newsroom_id'=>$this->id));
    }

    /**
     * 
     * @param UNL_ENews_User $user
     * 
     * @return bool
     */
    function addUser($user)
    {
        if ($user->hasNewsroomPermission($this->id)) {
            //User already has premission
            return true;
        }
        
        $permission = new UNL_ENews_User_Permission();
        $permission->newsroom_id = $this->id;
        $permission->user_uid    = $user->uid;
        
        if (!$permission->insert()) {
            return false;
        }
        
        if (1 == $user->newsroom_id) {
            //Change their default newsroom if it is currently the main one
            $user->newsroom_id = $this->id;
            $user->save();
        }
        
        return true;
    }

    /**
     * 
     * @param UNL_ENews_User $user
     * 
     * @return bool
     */
    function removeUser($user)
    {
        if ($permission = UNL_ENews_User_Permission::getById($user->uid, $this->id)) {
            return $permission->delete();
        }
        return true;
    }

    /**
     * Check if this newsroom has the story, regardless of status
     *
     * @param UNL_ENews_Story $story
     * 
     * @return false | UNL_ENews_Newsroom_Story
     */
    function hasStory(UNL_ENews_Story $story)
    {
        if ($has_story = UNL_ENews_Newsroom_Story::getById($this->id, $story->id)) {
            return $has_story;
        }

        return false;
    }

    function addStory(UNL_ENews_Story $story, $status = 'approved', UNL_ENews_User $user, $source = 'submit form')
    {
        if (false !== $this->hasStory($story)) {
            // Already have this story thanks
            return true;
        }
        $has_story = new UNL_ENews_Newsroom_Story();
        $has_story->newsroom_id  = $this->id;
        $has_story->story_id     = $story->id;
        $has_story->status       = $status;
        $has_story->uid_created  = $user->uid;
        $has_story->source       = $source;
        if ($result = $has_story->insert()) {
            return $result;
        }
        throw new Exception('Could not save the story', 500);
    }

    public static function archivePastStories()
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'UPDATE newsroom_stories
                JOIN stories ON newsroom_stories.story_id = stories.id
                SET newsroom_stories.status = "archived"
                WHERE stories.request_publish_end < "'.date('Y-m-d').'"
                    AND newsroom_stories.status != "archived"';
        return $mysqli->query($sql);
    }

    public function getURL()
    {
        return UNL_ENews_Controller::getURL().$this->shortname;
    }

    /**
     * Gets the URL users can submit news items for review
     *
     * @return string URL
     */
    public function getSubmitURL()
    {
        if (!empty($this->submit_url)) {
            return $this->submit_url;
        }

        if (empty($this->shortname)) {
            return '';
        }

        // Use default
        return $this->getURL().'/submit';
    }

    public static function getByOptions($options)
    {
        if (isset($options['shortname'])) {
            return self::getByShortname($options['shortname']);
        }

        $id = false;

        if (isset($options['newsroom'])) {
            $id = $options['newsroom'];
        }
        if (isset($options['newsroom_id'])) {
            $id = $options['newsroom_id'];
        }
        if (isset($options['id'])) {
            $id = $options['id'];
        }

        if ($id) {
            return self::getById($id);
        }

        return false;
    }
}
<?php
class UNL_ENews_Newsroom extends UNL_ENews_Record
{
    public $id;

    public $name;

    public $shortname;

    public $website;

    public $allow_submissions;

    public $footer_text;

    public $email_lists;

    public $from_address;

    function __construct($options = array())
    {

    }

    function getStories($status = 'pending')
    {
        return new UNL_ENews_Newsroom_Stories(array('newsroom_id'=>$this->id, 'status' => $status));
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
        if (!$user->hasNewsroomPermission($this->id)) {
            $permission = new UNL_ENews_User_Permission();
            $permission->newsroom_id = $this->id;
            $permission->user_uid    = $user->uid;
            return $permission->insert();
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

    function addStory(UNL_ENews_Story $story, $status = 'approved', UNL_ENews_User $user, $source = 'submit form')
    {
        if ($has_story = UNL_ENews_Newsroom_Story::getById($this->id, $story->id)) {
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
        $sql = 'UPDATE newsroom_stories, stories
                SET newsroom_stories.status = "archived"
                WHERE newsroom_stories.story_id = stories.id
                    AND stories.request_publish_end < "'.date('Y-m-d').'"
                    AND newsroom_stories.story_id != "archived"';
        return $mysqli->query($sql);
    }

    public function getURL()
    {
        return UNL_ENews_Controller::getURL().$this->shortname;
    }

    public function getSubmitURL()
    {
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
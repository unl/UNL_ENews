<?php
class UNL_ENews_Newsroom extends UNL_ENews_Record
{
    public $id;

    public $name;

    public $shortname;

    public $website;

    public $allow_submissions;

    public $email_lists;

    function __construct($options = array())
    {
        if (isset($options['result']) && $options['result'] instanceof mysqli_result) {
            $record = $options['result']->fetch_assoc();
            if(!$record){
                throw new Exception('That newsroom could not be found.', 403);
            }
        } elseif (isset($options['id'])) {
            $record = UNL_ENews_Record::getRecordByID('newsrooms', $options['id']);
        } else {
            $record = UNL_ENews_Record::getRecordByID('newsrooms', UNL_ENews_Controller::getUser(true)->newsroom_id);
        }
        UNL_ENews_Controller::setObjectFromArray($this, $record);

//        if (!UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($this->id)) {
//            throw new Exception('User does not have permission for this newsroom', 403);
//        }

        if (!empty($_POST)) {
            $this->handlePost();
        }
    }

    function handlePost()
    {
        if (!UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($this->id)) {
            throw new Exception('You cannot modify a newsroom you don\'t have permission to!', 403);
        }
        switch($_POST['_type']) {
            case 'removeuser':
            case 'adduser':
                $user = UNL_ENews_User::getByUID($_POST['user_uid']);

                $this->{$_POST['_type']}($user);
                break;
            case 'addemail':
                $this->{$_POST['_type']}($_POST['email'], $_POST['optout'], $_POST['newsletter_default']);
                break;
            case 'removeemail':
                $email = UNL_ENews_Newsroom_Email::getById($_POST['email_id']);
                $this->{$_POST['_type']}($email);
                break;
        }
        UNL_ENews_Controller::redirect(UNL_ENews_Controller::getURL().'?view=newsroom');
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
            throw new Exception('That email doesn\'t belong to you. Take off, eh!');
        }
        return $email->delete();
    }

    /**
     * 
     * @param string $shortname
     * 
     * @return UNL_ENews_Newsroom
     */
    public static function getByShortname($shortname)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsrooms WHERE shortname = '".$mysqli->escape_string($shortname)."' LIMIT 1;";
        if ($result = $mysqli->query($sql)) {
            $object = new self(array('result'=>$result));
            return $object;
        }
        return false;
    }

    /**
     * 
     * @param int $id
     * 
     * @return UNL_ENews_Newsroom
     */
    public static function getByID($id)
    {
        if ($record = UNL_ENews_Record::getRecordByID('newsrooms', $id)) {
            $object = new self(array('id'=>$id));
            UNL_ENews_Controller::setObjectFromArray($object, $record);
            return $object;
        }
        return false;
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
}
<?php
class UNL_ENews_Manager extends UNL_ENews_LoginRequired
{
    public $actionable = array();

    public $options = array('status'=>'pending', 'terms' => '');

    function __postConstruct()
    {
        try {
            if (isset($this->options['shortname'])) {
                if (!$newsroom = UNL_ENews_Newsroom::getByShortname($this->options['shortname'])) {
                    throw new Exception('Invalid newsroom!', 404);
                }
                $this->options['newsroom'] = $newsroom->id;
            }

            if (isset($this->options['newsroom'])) {
                $user = UNL_ENews_Controller::getUser(true);
                if ($user->newsroom_id != $this->options['newsroom']
                    && $user->hasNewsroomPermission((int)$this->options['newsroom'])) {
    
                    // Update the selected newsroom
                    $user->newsroom_id = (int)$this->options['newsroom'];
                    $user->update();
    
                    // Update the user record
                    UNL_ENews_Controller::setUser($user);
                }
            }
    
            UNL_ENews_Newsroom::archivePastStories();
            if (!empty($_POST)) {
                $this->handlePost();
            }
            $this->run();
        } catch(Exception $e) {
            $this->actionable[] = $e;
        }
    }

    function handlePost()
    {
        if (!UNL_ENews_Controller::validateCSRF()) {
            throw new \Exception('Invalid security token provided. If you think this was an error, please retry the request.', 403);
        }
        
        if (isset($_POST['newsroom'])) {
            $this->options['newsroom'] = $_POST['newsroom'];
        } else {
            unset($this->options['newsroom']);
        }
        switch($_POST['_type']) {
            case 'change_status':
                $this->processPostedStories();
                break;
            case 'newsroom':
                $newsroom = UNL_ENews_Newsroom::getByID($_POST['newsroom_id']);
                if (false === $newsroom) {
                    throw new Exception('Could not find the newsroom you were trying to edit!', 400);
                }

                if (!UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($newsroom->id)) {
                    throw new Exception('you cannot modify a newsroom you don\'t have permission to!', 403);
                }

                if (isset($_POST['allow_submissions'])
                    && $_POST['allow_submissions'] == 'on') {
                    $_POST['allow_submissions'] = 1;
                } else {
                    $_POST['allow_submissions'] = 0;
                }

                if (isset($_POST['private_web_view'])
                && $_POST['private_web_view'] == 'on') {
                $_POST['private_web_view'] = 1;
                } else {
                $_POST['private_web_view'] = 0;
                }

                $newsroom->synchronizeWithArray($_POST);
                $newsroom->save();

                UNL_ENews_Controller::redirect(UNL_ENews_Controller::getURL().'?view=newsroom');
                break;
        }
    }

    function run()
    {
        $term = isset($this->options['term']) ? $this->options['term'] : '';
        switch($this->options['status']) {
            case 'pending':
            case 'posted':
            case 'approved':
            case 'archived':
                if (!UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($this->newsroom->id)) {
                    throw new Exception('Don\'t have permission to view that newsroom', 403);
                }
                $this->actionable[] = new UNL_ENews_User_Newsrooms(array('uid' => UNL_ENews_Controller::getUser(false)->uid));
                $this->actionable[] = new UNL_ENews_Newsroom_Stories(
                    $this->options + array(
                        'status'      => $this->options['status'],
                        'term'        => $term,
                        'newsroom_id' => $this->newsroom->id
                    )
                );
                break;
        }
    }

    /**
     * Runs actions on the posted stories.
     *
     * @return bool
     */
    public function processPostedStories()
    {
        $stories         = self::getPostedStories();
        $stories_changed = false;
        if (count($stories)) {
            foreach ($stories as $story) {
                if ($this->processPostStatusChange($story)) {
                    $stories_changed = true;
                }
            }
        }
        return $stories_changed;
    }

    /**
     * This function returns an array of all posted stories.
     * stories should be posted in the form story_1923 Where 1923 is
     * the ID of the story.
     *
     * @return array(UNL_ENews_StoryList)
     */
    static public function getPostedStories()
    {
        $stories = array();
        foreach ($_POST as $key=>$value) {
            $matches = array();
            if (preg_match('/story_([\d]+)/', $key, $matches)) {
                $stories[] = $matches[1];
            }
        }
        return new UNL_ENews_StoryList($stories);
    }

    /**
     * Handles the posting of an updated story. This will alter the story's status
     * based on what the user chose within the manager interface.
     *
     * @param UNL_ENews_Story $story  Story to update.
     * @param string          $source Source of this change in status.
     *
     * @return bool
     */
    function processPostStatusChange($story, $source='search')
    {
        if (isset($this->options['newsroom'])) { // view=manager

            if (!UNL_ENews_User_Permission::userHasNewsroomPermission(UNL_ENews_Controller::getUser()->uid, $this->newsroom->id)) {
                throw new Exception('You do not have permission to edit this newsroom', 403);
            }

            $has_story = UNL_ENews_Newsroom_Story::getById($this->newsroom->id, $story->id);

            if (false === $has_story) {
                throw new Exception('That story is not associated with this newsroom.');
            }

            if (isset($_POST['delete'])) {

                // Check if this story has been published
                foreach ($story->getNewsletters() as $newsletter) {
                    /* @var $newsletter UNL_ENews_Newsletter */
                    if (isset($newsletter->release_date)
                        && (strtotime($newsletter->release_date) < time())
                        && $newsletter->newsroom_id == $this->newsroom->id) {
                        throw new Exception('That story has been published in one of your newsletters. If you really want to delete it, first remove it from the newsletter. '.$newsletter->getEditURL(), 403);
                    }
                }

                if (count($story->getNewsrooms()) === 1 ) {
                    // Story only belongs to one newsroom, delete entirely
                    return $story->delete();
                }

                // Remove the story from this newsroom only
                return $has_story->delete();
            } elseif (isset($_POST['pending'])) {
                $has_story->status = 'pending';
                return $has_story->save();
            } elseif (isset($_POST['approved'])) {
                $has_story->status = 'approved';
                return $has_story->save();
            }
        } else { // view=mynews

            if (!isset($_POST['delete'])) {
                return false;
            }

            if (UNL_ENews_Controller::getUser()->uid !== $story->uid_created) {
                throw new Exception('You did not create that story - you cannot delete it', 403);
            }

            foreach ($story->getNewsrooms() as $newsroom) {
                if (UNL_ENews_Newsroom_Story::getById($newsroom->id, $story->id)->status !== 'pending') {
                    throw new Exception('A story you attempted to delete has already been approved for use by a newsroom', 403);
                }
            }

            return $story->delete();
        }
        return false;
    }

    function __get($var)
    {
        switch($var) {
            case 'newsroom':
                return UNL_ENews_Controller::getUser(true)->newsroom;
        }
        return false;
    }
}
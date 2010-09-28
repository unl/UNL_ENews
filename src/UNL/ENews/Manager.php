<?php
class UNL_ENews_Manager extends UNL_ENews_LoginRequired
{
    public $actionable = array();
    
    public $options = array('status'=>'pending');
    
    function __postConstruct()
    {
        if (isset($this->options['newsroom'])) {
            $user = UNL_ENews_Controller::getUser(true);
            if ($user->newsroom_id != $this->options['newsroom']
                && $user->hasPermission($this->options['newsroom'])) {
                // Update the selected newsroom
                $user->newsroom_id = $this->options['newsroom'];
                $user->update();
            }
        }
        UNL_ENews_Newsroom::archivePastStories();
        if (!empty($_POST)) {
            try {
                $this->handlePost();
            } catch(Exception $e) {
                $this->actionable[] = $e;
            }
        }
        $this->run();
    }
    
    function handlePost()
    {
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

                if (!UNL_ENews_Controller::getUser(true)->hasPermission($newsroom->id)) {
                    throw new Exception('you cannot modify a newsroom you don\'t have permission to!', 403);
                }

                if (isset($_POST['allow_submissions'])
                    && $_POST['allow_submissions'] == 'on') {
                    $_POST['allow_submissions'] = 1;
                } else {
                    $_POST['allow_submissions'] = 0;
                }
                UNL_ENews_Controller::setObjectFromArray($newsroom, $_POST);
                $newsroom->save();

                UNL_ENews_Controller::redirect(UNL_ENews_Controller::getURL().'?view=newsroom');
                break;
        }
    }
    
    function run()
    {
        switch($this->options['status']) {
            case 'pending':
            case 'posted':
            case 'approved':
            case 'archived':
                if (!UNL_ENews_Controller::getUser(true)->hasPermission($this->newsroom->id)) {
                    throw new Exception('Don\'t have permission to view that newsroom', 403);
                }
                $this->actionable[] = new UNL_ENews_User_Newsrooms(array('uid' => UNL_ENews_Controller::getUser(false)->uid));
                $this->actionable[] = new UNL_ENews_Newsroom_Stories($this->options + array('status'      => $this->options['status'],
                                                                                            'newsroom_id' => $this->newsroom->id));
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
        if (isset($this->options['newsroom'])) { //view=manager
            $has_story = UNL_ENews_Newsroom_Story::getById($this->newsroom->id, $story->id);
            if (isset($_POST['delete'])) {
                if (count($story->getNewsrooms()) === 1 ) {
                    //Story only belongs to one newsroom, delete entirely
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
        } else { //view=mynews
            if (isset($_POST['delete'])) {
                if (UNL_ENews_Controller::getUser()->uid !== $story->uid_created) {
                    throw new Exception('You did not create that story - you can not delete it', 403);
                }
                $newsrooms = new UNL_ENews_Story_Newsrooms(array('id'=>$story->id));
                foreach ($newsrooms as $newsroom) {
                    if (UNL_ENews_Newsroom_Story::getById($newsroom->id,$story->id)->status !== 'pending') { //@TODO hmmm what about archived?
                        throw new Exception('A story you attempted to delete has already been approved for use by a newsroom', 403);
                    }
                }
                return $story->delete();
            }
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
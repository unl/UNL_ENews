<?php
class UNL_ENews_Manager extends UNL_ENews_LoginRequired
{
    public $actionable = array();
    
    public $options = array('status'=>'pending');
    
    function __postConstruct()
    {
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
        switch($_POST['_type']) {
            case 'change_status':
                $this->processPostedStories();
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
            	if (UNL_ENews_Controller::getUser(true)->hasPermission($this->newsroom->id)) {
                	$this->actionable[] = new UNL_ENews_Newsroom_Stories(array('status'      => $this->options['status'],
                                                                           'newsroom_id' => $this->newsroom->id));
            	} else {
            		throw new Exception('Don\'t have permission to view that newsroom');
            	}
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
     * @param UNL_UCBCN_Story $story  Story to update.
     * @param string          $source Source of this change in status.
     *
     * @return bool
     */
    function processPostStatusChange($story, $source='search')
    {
        if ($has_story = UNL_ENews_Newsroom_Story::getById($this->newsroom->id, $story->id)) {

            // This event date time combination was selected... find out what they chose.
            if (isset($_POST['delete'])) {
                // User has chosen to delete the story selected, and has permission to delete the story.
                if ($has_story->source == 'submit form') {
                    // This is the newsroom the story was originally created on, delete from the entire system.
                    return $story->delete();
                }
                // Remove the story from this newsroom
                return $has_story->delete();
            } elseif (isset($_POST['pending'])) {
                $has_story->status = 'pending';
                return $has_story->save();
            } elseif (isset($_POST['approved'])) {
                $has_story->status = 'approved';
                return $has_story->save();
            }
        } else {
            $has_story = new UNL_ENews_Newsroom_Story();
            if (isset($_POST['pending'])) {
                $has_story->status = 'pending';
                $has_story->source = $source;
                return $has_story->save();
            } elseif (isset($_POST['approved'])) {
                $has_story->status = 'approved';
                $has_story->source = $source;
                return $has_story->save();
            }
        }
        return false;
    }
    
    function __get($var)
    {
        switch($var) {
            case 'newsroom':
       			if (isset($this->options['newsroom'])) {
            		$newsroom = UNL_ENews_Newsroom::getByID($this->options['newsroom']);
            	} else {
            		$newsroom = UNL_ENews_Controller::getUser(true)->newsroom;
            	}
                return $newsroom;
        }
        return false;
    }
}
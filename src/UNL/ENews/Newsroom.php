<?php
class UNL_ENews_Newsroom extends UNL_ENews_Record
{
    
    public $id;

    public $name;
    
    public $shortname;
    
    public $website;
    
    function getStories($status = 'pending')
    {
        return new UNL_ENews_Newsroom_Stories(array('newsroom_id'=>$this->id, 'status' => $status));
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
            $object = new self();
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
    
    function addStory(UNL_ENews_Story $story, $status = 'approved', UNL_ENews_User $user)
    {
        if ($has_story = UNL_ENews_Newsroom_Story::getById($this->id, $story->id)) {
            echo $story->id;
            echo ' I already have this one!';
            // Already have this story thanks
            return true;
        }
        $has_story = new UNL_ENews_Newsroom_Story();
        $has_story->newsroom_id  = $this->id;
        $has_story->story_id     = $story->id;
        $has_story->status       = $status;
        $has_story->uid_created  = $user->uid;
        if ($result = $has_story->insert()) {
            return $result;
        }
        throw new Exception('Could not save the story');
    }
}
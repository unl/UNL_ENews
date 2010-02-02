<?php
class UNL_ENews_Newsroom extends UNL_ENews_Record
{
    
    public $id;

    public $name;
    
    public $shortname;
    
    public $website;
    
    function getStories($type = 'pending')
    {
        
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
    
    function addStory(UNL_ENews_Story $story, $status = 'posted', UNL_ENews_User $user)
    {
        $has_story = new UNL_ENews_Newsroom_Story();
        $has_story->newsroom_id  = $this->id;
        $has_story->story_id     = $story->id;
        $has_story->date_created = date('Y-m-d H:i:s');
        $has_story->status       = $status;
        $has_story->uid_created  = $user->uid;
    }
}
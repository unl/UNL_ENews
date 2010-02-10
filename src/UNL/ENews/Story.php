<?php
class UNL_ENews_Story extends UNL_ENews_Record
{
    public $id;
    public $title;
    public $description;
    public $request_publish_start;
    public $request_publish_end;
    public $sponsor;
    public $website;
    public $uid_created;
    public $date_submitted;
    
    function getTable()
    {
        return 'stories';
    }
    
    function save()
    {
        $this->event_date       = $this->getDate($this->event_date);
        $this->uid_created      = strtolower(UNL_ENews_Controller::getUser(true)->uid);
        $this->date_submitted   = date('Y-m-d H:i:s');
        $result = parent::save();
        
        if (!$result) {
            throw new Exception('Error saving your story.');
        }
        
        // Add it to the default newsroom
        if (!UNL_ENews_Controller::getUser(true)->newsroom->addStory($this,
                                                                     'pending',
                                                                     UNL_ENews_Controller::getUser(true),
                                                                     'submit form')) {
            throw new Exception('Could not add the story to the default newsroom');
        }
        
        return true;
    }
    
    /**
     * 
     * @param unknown_type $id
     * @return UNL_ENews_Story
     */
    public static function getByID($id)
    {
        if ($record = UNL_ENews_Record::getRecordByID('stories', $id)) {
            $object = new self();
            UNL_ENews_Controller::setObjectFromArray($object, $record);
            return $object;
        }
        return false;
    }
    
    public function addFile(UNL_ENews_File $file)
    {
        $has_file = new UNL_ENews_Story_File();
        $has_file->file_id  = $file->id;
        $has_file->story_id = $this->id;
        return $has_file->save();
    }
    
    function getFiles()
    {
        return new UNL_ENews_Story_Files(array('story_id'=>$this->id));
    }
    
    function delete()
    {
        foreach ($this->getFiles() as $file) {
            $file->delete();
        }
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'DELETE FROM newsroom_stories WHERE story_id = '.intval($this->id);
        $mysqli->query($sql);
        $sql = 'DELETE FROM newsletter_stories WHERE story_id = '.intval($this->id);
        $mysqli->query($sql);
        return parent::delete();
    }
}
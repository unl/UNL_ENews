<?php
class UNL_ENews_Story extends UNL_ENews_Record
{
    public $id;
    public $title;
    public $description;
    public $full_article;
    public $request_publish_start;
    public $request_publish_end;
    public $sponsor;
    public $website;
    public $uid_created;
    public $date_submitted;

    function __construct($options = array())
    {
    	if (isset($options['id'])) {
    		$story = self::getByID($options['id']);
    		$this->id 			= $story->id;
    		$this->title 		= $story->title;
    		$this->description 	= $story->description;
            $this->full_article = $story->full_article;
    		$this->website		= $story->website;
    	}
    }
      
    function getTable()
    {
        return 'stories';
    }
    
    function save()
    {
        $this->request_publish_start = $this->getDate($this->request_publish_start);
        $this->request_publish_end   = $this->getDate($this->request_publish_end);
        $this->uid_created           = strtolower(UNL_ENews_Controller::getUser(true)->uid);
        $this->date_submitted        = date('Y-m-d H:i:s');
        $result = parent::save();
        
        if (!$result) {
            throw new Exception('Error saving your story.');
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
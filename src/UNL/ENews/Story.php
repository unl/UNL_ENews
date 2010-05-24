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
    public $uid_modified;
    public $date_submitted;
    public $date_modified;

    /**
     * Construct a new story
     * 
     * @param $options = array([id])
     */
    function __construct($options = array())
    {
        if (isset($options['id'])) {
            $story = self::getByID($options['id']);
            UNL_ENews_Controller::setObjectFromArray($this, $story->toArray());
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

        if (empty($this->id)) {
            $this->uid_created    = strtolower(UNL_ENews_Controller::getUser(true)->uid);
            $this->date_submitted = date('Y-m-d H:i:s');
        } else {
            $this->uid_modified   = strtolower(UNL_ENews_Controller::getUser(true)->uid);
            $this->date_modified  = date('Y-m-d H:i:s');
        }
        $result = parent::save();
        
        if (!$result) {
            throw new Exception('Error saving your story.', 500);
        }
        
        return true;
    }

    /**
     * Retrieve a story
     * 
     * @param int $id
     * 
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

    /**
     * Retrieve newsrooms a story belongs to
     * 
     * @param 
     * 
     * @return UNL_ENews_Story_Newsrooms array
     */
    function getNewsrooms()
    {
        return new UNL_ENews_Story_Newsrooms(array('id'=>$this->id));
    }

    /**
     * Add a related file to this story.
     * 
     * @param UNL_ENews_File $file The file to add
     */
    public function addFile(UNL_ENews_File $file)
    {
        $has_file = new UNL_ENews_Story_File();
        $has_file->file_id  = $file->id;
        $has_file->story_id = $this->id;
        return $has_file->insert();
    }
    
    /**
     * Remove a related file
     *
     * @param UNL_ENews_File $file The file to remove
     */
    public function removeFile(UNL_ENews_File $file)
    {
        if ($has_file = UNL_ENews_Story_File::getById($this->id, $file->id)) {
            return $has_file->delete();
        }
        return true;
    }
    
    /**
     * Retrieve all files related to this story
     *
     * @return UNL_ENews_Story_Files
     */
    function getFiles()
    {
        return new UNL_ENews_Story_Files(array('story_id'=>$this->id));
    }
    
    /**
     * Retrieves the first file found matching the use given.
     * 
     * @param string $use Type of use eg: thumbnail
     * 
     * @return UNL_ENews_Story_File
     */
    function getFileByUse($use)
    {
        $files = new UNL_ENews_Story_Files(array('story_id'=>$this->id));
        foreach ($files as $file) {
            if ($file->use_for == $use) {
                return $file;
            }
        }
        return false;
    }

    /**
     * Gets the thumbnail, if any
     *
     * @return UNL_ENews_File_Image
     */
    function getThumbnail()
    {
        return $this->getFileByUse('thumbnail');
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

    /**
     * Check if the user has permission to edit this story.
     *
     * @param UNL_ENews_User $user The user to check
     */
    function userCanEdit(UNL_ENews_User $user)
    {
        if ($user->uid == $this->uid_created) {
            return true;
        }
        
        foreach ($user->newsrooms as $newsroom) {
            if (UNL_ENews_Newsroom_Stories::relationshipExists($newsroom->id, $this->id)) {
                return true;
            }
        }
        
        return false;
    }
}
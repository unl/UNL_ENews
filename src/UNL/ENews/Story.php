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
    public $presentation_id;
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
    function getFileByUse($use, $create = false)
    {
        $db = UNL_ENews_Controller::getDB();
        $sql = "SELECT files.* FROM story_files, files WHERE story_files.story_id = ".(int)$this->id." AND story_files.file_id = files.id AND files.use_for = '".$db->escape_string($use)."';";
        if (($result = $db->query($sql))
            && $result->num_rows > 0) {
            return UNL_ENews_File::newFromArray($result->fetch_assoc());
        }

        if (true === $create
            && $file = $this->getFileByUse('originalimage', false)) {
            switch ($use) {
            case 'thumbnail':
                $new = $file->saveThumbnail();
                break;
            case UNL_ENews_File_Image::MAX_WIDTH.'_wide':
                $new = $file->saveMaxWidth();
                break;
            case UNL_ENews_File_Image::HALF_WIDTH.'_wide':
                $new = $file->saveHalfWidth();
                break;
            default:
                throw new Exception('I cannot create that for you.');
            }
            if ($new) {
                $this->addFile($new);
                return $new;
            }
        }

        return false;
    }

    function getURL()
    {
        return UNL_ENews_Controller::getURL().'?view=story&id='.$this->id;
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

    function __get($var)
    {

        switch ($var) {
        case 'presentation':
        case 'newsrooms':
        case 'thumbnail':
        case 'files':
            $method = 'get'.$var;
            return $this->$method();
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $var .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    function getPresentationTypeString()
    {
        if ($presentation = $this->getPresentation()) {
            return $presentation->type;
        }
        return false;
    }

    /**
     * Get the presentation to be used for this story.
     *
     * @return UNL_ENews_Story_Presentation
     */
    function getPresentation()
    {
        $presentation = UNL_ENews_Story_Presentation::getByID($this->presentation_id);

        if (false == $presentation) {
            throw new Exception('This story references a presentation type that is unknown!');
        }
        return $presentation;
    }

    function isWithinRequestedPublishDate()
    {
        $now = time();
        if ($now < strtotime($this->request_publish_start)) {
            // Not ready to release yet
            return false;
        }

        if ($now > strtotime($this->request_publish_end)) {
            // Expired content/advertisement
            return false;
        }

        return true;
    }
}
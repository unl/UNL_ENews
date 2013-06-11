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
            $this->synchronizeWithArray($story->toArray());
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
            $this->date_modified  = date('Y-m-d H:i:s');
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
            $object->synchronizeWithArray($record);
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
     * Retrieve newsletters a story belongs to
     *
     * @param
     *
     * @return UNL_ENews_Story_Newsletters
     */
    function getNewsletters()
    {
        return new UNL_ENews_Story_Newsletters(array('id'=>$this->id));
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

    public function deleteFiles()
    {
        $files = $this->getFiles();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'DELETE FROM story_files WHERE story_id = '.intval($this->id);
        $mysqli->query($sql);
        foreach ($files as $file) {
            $file->delete();
        }
    }

    /**
     * Retrieve all files related to this story
     *
     * @return UNL_ENews_Story_Files
     */
    function getFiles()
    {
        if (empty($this->id)) {
            throw new Exception('This story doesn\'t have a valid ID.', 400);
        }

        return new UNL_ENews_Story_Files(array('story_id'=>$this->id));
    }

	/**
     * Add a related tag to this story.
     *
     * @param UNL_ENews_Tag $tag The tag to add
     */
    public function addTag(UNL_ENews_Tag $tag)
    {
        $has_tag = new UNL_ENews_Story_Tag();
        $has_tag->tag_id   = $tag->id;
        $has_tag->story_id = $this->id;
        return $has_tag->insert();
    }

	/**
     * Remove tag
     *
     * @param UNL_ENews_Tag $tag The tag to remove
     */
    public function removeTag(UNL_ENews_Tag $tag)
    {
        if ($has_tag = UNL_ENews_Story_Tag::getById($this->id, $tag->id)) {
            return $has_tag->delete();
        }
        return true;
    }

	/**
     * Retrieve all tags related to this story
     *
     * @return UNL_ENews_Story_Tags
     */
    function getTags()
    {
        return new UNL_ENews_Story_Tags(array('story_id'=>$this->id));
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
            default:
                if (!preg_match('/([\d]+)_wide/', $use, $matches)) {
                    throw new Exception('I cannot create that for you.', 405);
                }
                $method = 'save'.$matches[1].'Width';
                $new = $file->$method();
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
        return UNL_ENews_Controller::getURL().'stories/'.$this->id;
    }

    function getEditURL()
    {
        return UNL_ENews_Controller::getURL().'?view=submit&id='.$this->id;
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
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'DELETE FROM newsroom_stories WHERE story_id = '.intval($this->id);
        $mysqli->query($sql);
        $sql = 'DELETE FROM newsletter_stories WHERE story_id = '.intval($this->id);
        $mysqli->query($sql);
        $this->deleteFiles();
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
            throw new Exception('This story references a presentation type that is unknown!', 500);
        }
        return $presentation;
    }

    function hasNotExpired($time = null)
    {
        if ($time == null) {
            $time = time();
        }

        $end_time = substr($this->request_publish_end, 0, 10).' + 1 day';
        if ($time > strtotime($end_time)) {
            // Expired content/advertisement
            return false;
        }

        return true;
    }

    function pastPublishStart($time = null)
    {
        if ($time == null) {
            $time = time();
        }

        $start = strtotime($this->request_publish_start);
        if (false !== $start
            && $start < $time) {
            return true;
        }

        return false;
    }

    function withinPublishRange($time = null)
    {
        if ($time == null) {
            $time = time();
        }

        if ($this->pastPublishStart($time)
            && $this->hasNotExpired($time)) {
            return true;
        }

        return false;
    }

    function toExtendedArray()
    {
        $story_data = $this->toArray();
        $files_data = array();
        foreach ($this->getFiles() as $id=>$file) {
            $file_data = $file->toArray();
            unset($file_data['data']);
            $files_data[$file->getURL()] = $file_data;
        }
        $story_data['files'] = $files_data;
        return $story_data;
    }
}
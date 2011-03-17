<?php
class UNL_ENews_PostHandler
{
    public $options = array();
    public $post    = array();
    public $files   = array();
    
    function __construct($options = array(), $post = array(), $files = array())
    {
        $this->options = $options;
        $this->post    = $post;
        $this->files   = $files;
    }

    /**
     * Filter any pre-populated POST fields to prevent their use.
     *
     * @return void
     */
    function filterPostValues()
    {
        unset($this->post['uid']);
        unset($this->post['id']);
    }

    function handle()
    {
        $this->filterPostValues();

        if (!isset($this->post['_type'])) {
            // Nothing to do here
            return;
        }

        switch ($this->post['_type']) {
            case 'story':
            case 'file':
            case 'deletenewsletter':
                $method = 'handle' . $this->post['_type'];
                return $this->$method();
        }
    }

    protected function getPostedStory()
    {
        if (!empty($this->post['storyid'])) {
            if (!($story = UNL_ENews_Story::getByID($this->post['storyid']))) {
                throw new Exception('The story could not be retrieved');
            }
            if (!$story->userCanEdit(UNL_ENews_Controller::getUser(true))) {
                throw new Exception('You cannot edit that story.');
            }
        } else {
            $story = new UNL_ENews_Story;
        }
        return $story;
    }
    
    function handleStory()
    {
        $story = $this->getPostedStory();
        $story->synchronizeWithArray($this->post);

        if (!$story->save()) {
            throw new Exception('Could not save the story');
        }

        foreach ($this->post['newsroom_id'] as $id) {
            if (!empty($id)) {
                if (!$newsroom = UNL_ENews_Newsroom::getByID($id)) {
                    throw new Exception('Invalid newsroom selected');
                }
                $status = 'pending';
                if (UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($newsroom->id)) {
                    $status = 'approved';
                }
                $newsroom->addStory($story, $status, UNL_ENews_Controller::getUser(true), 'create story form');
            }
        }

        $original = $story->getFileByUse('originalimage');

        if (!empty($this->post['fileID'])
            && false !== $original
            && $original->id != $this->post['fileID']) {

            // We've got a new original image we're working with, delete all the old ones.
            $story->removeFile($original);
            $original->delete();

            foreach ($story->getFiles() as $old_file) {
                $story->removeFile($old_file);
                $old_file->delete();
            }
        }

        if ($file = UNL_ENews_File::getById($this->post['fileID'])) {
            $file->description = $this->post['fileDescription'];
            $file->save();

            $thumbnail = false;
            // A story being edited has default thumbnail coords of -1 to ensure the current thumbnail is not overwritten if new coords are not selected
            if ($this->post['thumbX1'] >= 0 &&
                $this->post['thumbX2'] >= 0 &&
                $this->post['thumbY1'] >= 0 &&
                $this->post['thumbY2'] >= 0) {
                //Delete existing thumbnail
                if ($oldThumbnail = $story->getThumbnail()) {
                    $story->removeFile($oldThumbnail);
                    $oldThumbnail->delete();
                }
                $thumbnail = $file->saveThumbnail($this->post['thumbX1'],$this->post['thumbX2'],$this->post['thumbY1'],$this->post['thumbY2']);
            }

            // Get existing story_files connections and add the files if no connection exists
            $story_files = $story->getFiles();
            if (!in_array($file->id, $story_files->getArrayCopy())) {
                $story->addFile($file);
            }
            if ($thumbnail && !in_array($thumbnail->id, $story_files->getArrayCopy())) {
                $story->addFile($thumbnail);
            }
        }

        self::redirect(UNL_ENews_Controller::getURL().'?view=thanks&_type='.$this->post['_type'].'&id='.(int)$story->id);
    }

    public function handleFile()
    {
        if ($this->files['image']['error'] != UPLOAD_ERR_OK) {
            throw new Exception("Error Uploading File!");
        }

        $file = new UNL_ENews_File;

        $file_data         = $this->files['image'];
        $file_data['data'] = file_get_contents($this->files['image']['tmp_name']);

        $file->synchronizeWithArray($file_data);

        if (isset($this->options['ajaxupload'])) {
            if (!UNL_ENews_File::validFileName($this->files['image']['name'])) {
                throw new Exception('Please Upload an Image in .jpg .png or .gif format.');
            }
            $file->use_for = 'originalimage';
        }

        if (!$file->save()) {
            throw new Exception('Error saving the file');
        }

        if (!isset($this->options['ajaxupload'])) {
            self::redirect(UNL_ENews_Controller::getURL().'?view=thanks&_type='.$this->post['_type']);
        }

        // Return the id as the response
        echo $file->id;
        exit();
    }
    
    function handleDeleteNewsletter()
    {
        if (!($newsletter = UNL_ENews_Newsletter::getByID($this->post['newsletter_id']))) {
            throw new Exception('Invalid newsletter selected for delete');
        }

        if (UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($newsletter->newsroom_id)) {
            $newsletter->delete();
        }
    }

    static function redirect($url, $exit = true)
    {
        UNL_ENews_Controller::redirect($url, $exit);
    }
}
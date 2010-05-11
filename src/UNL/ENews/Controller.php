<?php
class UNL_ENews_Controller
{
    /**
     * Options array
     * Will include $_GET vars, this is the newsroom being used across views 
     */
    public $options = array('view' => 'submit', 'format' => 'html', 'newsroom' => '1');
    
    protected $view_map = array('newsletter'  => 'UNL_ENews_Newsletter_Public',
                                'latest'      => 'UNL_ENews_StoryList_Latest',
                                'story'       => 'UNL_ENews_Story',
                                'submit'      => 'UNL_ENews_Submission',
                                'thanks'      => 'UNL_ENews_Confirmation',
                                'manager'     => 'UNL_ENews_Manager',
                                'file'        => 'UNL_ENews_File',
                                'preview'     => 'UNL_ENews_Newsletter_Preview',
                                'newsletters' => 'UNL_ENews_Newsroom_Newsletters',
                                'sendnews'    => 'UNL_ENews_EmailDistributor'
    ); 
    
    public static $pagetitle = array('latest'      => 'Latest News',
                                     'submit'      => 'Submit an Item',
                                     'manager'     => 'Manage News',
                                     'preview'     => 'Build Newsletter',
                                     'newsletters' => 'Newsletters',
    );
    
    protected static $auth;
    
    protected static $admins = array('admin'
        );
    
    /**
     * The currently logged in user.
     * 
     * @var UNL_ENews_User
     */
    protected static $user = false;
    
    public static $url = '';
    
    public static $db_user = 'enews';
    
    public static $db_pass = 'enews';
    
    public $actionable = array();
    
    function __construct($options)
    {
        $options += $this->options;
        $this->options = $options;
        $this->authenticate(true);
        
        if (!empty($_POST)) {
            try {
                $this->handlePost();
            } catch(Exception $e) {
                $this->actionable[] = $e;
            }
        }
        
        try {
            $this->run();
        } catch(Exception $e) {
            $this->actionable[] = $e;
        }
    }
    
    public static function setAdmins($admins = array())
    {
        self::$admins = $admins;
    }
    
    /**
     * Log in the current user
     * 
     * @return void
     */
    static function authenticate($logoutonly = false)
    {
        if (isset($_GET['logout'])) {
            self::$auth = UNL_Auth::factory('SimpleCAS');
            self::$auth->logout();
        }
        if ($logoutonly) {
            return true;
        }

        self::$auth = UNL_Auth::factory('SimpleCAS');
        self::$auth->login();
        
        if (!self::$auth->isLoggedIn()) {
            throw new Exception('You must log in to view this resource!');
            exit();
        }
        self::$user = UNL_ENews_User::getByUID(self::$auth->getUser());
        self::$user->last_login = date('Y-m-d H:i:s');
        self::$user->update();
    }
    
    /**
     * get the currently logged in user
     * 
     * @return UNL_ENews_User
     */
    public static function getUser($forceAuth = false)
    {
        if (self::$user) {
            return self::$user;
        }
        
        if ($forceAuth) {
            self::authenticate();
        }
        
        return self::$user;
    }
    
    function handlePost()
    {
        $this->filterPostValues();
        switch($_POST['_type']) {
            case 'story':
                $class = $this->view_map[$_POST['_type']];
                if (!empty($_POST['storyid'])) {
                    $object = UNL_ENews_Story::getByID($_POST['storyid']);
                    if (!$object->userCanEdit(UNL_ENews_Controller::getUser(true))) {
                        throw new Exception('You cannot edit that story.');
                    }
                } else {
                    $object = new $class();
                }
                self::setObjectFromArray($object, $_POST);
                
                if (!$object->save()) {
                    throw new Exception('Could not save the story');
                } 
                
                foreach ($_POST['newsroom_id'] as $id) {
                    if ($newsroom = UNL_ENews_Newsroom::getByID($id)) {
                        $status = 'pending';
                        if (UNL_ENews_Controller::getUser(true)->hasPermission($newsroom->id)) {
                            $status = 'approved';
                        }
                        $newsroom->addStory($object, $status, UNL_ENews_Controller::getUser(true), 'create event form');
                    } else {
                        throw new Exception('Invalid newsroom selected');
                    }
                }
                if (isset($_POST['ajaxupload'])) {
                    echo $object->id;
                    exit();
                } else {
                    header('Location: ?view=thanks&_type='.$_POST['_type']);
                    exit();
                }
                break;
            case 'file':
                $class = $this->view_map[$_POST['_type']];
                if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $file_data = $_FILES['image'];
                    $file_data['data'] = file_get_contents($_FILES['image']['tmp_name']);
                    $file = new $class();
                    self::setObjectFromArray($file, $file_data);
                    $story = UNL_ENews_Story::getByID((int)$_POST['storyid']);
                    if (isset($this->options['ajaxupload'])) {
                        $allowedExtensions = array("gif","jpeg","jpg","png");
                        if (!in_array(end(explode(".",strtolower($_FILES['image']['name']))),$allowedExtensions)) {
                            echo 'Please Upload an Image in .jpg .png or .gif format.';
                            exit();
                        }
                        $file->use_for = 'originalimage'; 
                    }
                    if ($file->save()) {
                        $story->addFile($file);
                        if (!isset($this->options['ajaxupload'])) {
                            header('Location: ?view=thanks&_type='.$_POST['_type']);
                        } else {
                            //We're doing the ajax upload in step 3 of the submission form, so delete the previous photo
                            foreach ($story->getFiles() as $curfile) {
                                if (preg_match('/^image/', $curfile->type)) {
                                    //Check to see that we Don't Delete the File we just uploaded
                                    if ($curfile->id != $file->id) { 
                                        $curfile->delete();
                                        $mysqli = UNL_ENews_Controller::getDB();
                                        $sql = 'DELETE FROM story_files WHERE story_id = '.intval($story->id).' AND file_id = '.intval($curfile->id);
                                        $mysqli->query($sql); 
                                    }
                                }
                            }
                            //Output the image that will be shown on step 3 of submission page
                            header('Location: ?view=file&id='.$file->id);
                            exit();
                        }
                    } else {
                        throw new Exception('Error saving the file');
                    }
                    exit();
                } else {
                    echo "Error Uploading File!";
                    exit();
                }
                break;
            case 'savethumb':
                if (!$story = UNL_ENews_Story::getByID((int)$_POST['storyid'])) {
                    throw new Exception('Could not find that story!');
                }
                //If there is an existing thumbnail we know we're in editing mode...
                //...and if no coords have been selected we keep existing thumbnail and exit
                if ($story->getFileByUse('thumbnail') && empty($_POST['x1'])) {
                    header('Location: ?view=thanks&_type=story');
                    exit();
                }
                //Delete existing thumbnail
                if ($thumb = $story->getFileByUse('thumbnail')) {
                    $thumb->delete();
                }
                $mysqli = UNL_ENews_Controller::getDB();
                $sql = 'DELETE FROM story_files WHERE story_id = '.intval($story->id).' AND file_id = '.intval($thumb->id);
                $mysqli->query($sql); 
                
                $file = $story->getFileByUse('originalimage');
                $newfile = new UNL_ENews_File();
                $newfile = $file;
                
                // Crop the image ***************************************************************
                // Get dimensions of the original image
                $filename = UNL_ENews_Controller::getURL().'?view=file&id='.$file->id;
                list($current_width, $current_height) = getimagesize($filename);
                
                if (empty($_POST['x1'])) {
                    // User did not select a cropping area
                    $left = 0;
                    $top = 0;
                    $right = $current_width;
                    $bottom = $current_width*(3/4);
                } else {
                    // Needs to be adjusted to account for the scaled down 410px-width size that's displayed to the user
                    if ($current_width > 410) {
                        $left = ($current_width/410)*$_POST['x1'];
                        $top = ($current_height/(410*$current_height/$current_width))*$_POST['y1'];
                        $right = ($current_width/410)*$_POST['x2'];
                        $bottom = ($current_height/(410*$current_height/$current_width))*$_POST['y2'];
                    } else {
                        $left = $_POST['x1'];
                        $top = $_POST['y1'];
                        $right = $_POST['x2'];
                        $bottom = $_POST['y2'];
                    }
                }
                
                // This will be the final size of the cropped image
                $crop_width = $right-$left;
                $crop_height = $bottom-$top;
                
                // Resample the image
                $croppedimage = imagecreatetruecolor($crop_width, $crop_height);
                switch ($file->type) {
                    case 'image/jpeg':
                        $current_image = imagecreatefromjpeg($filename);
                        break;
                    case 'image/png':
                        $current_image = imagecreatefrompng($filename);
                        break;
                    case 'image/gif':
                        $current_image = imagecreatefromgif($filename);
                        break;
                }
                imagecopy($croppedimage, $current_image, 0, 0, $left, $top, $current_width, $current_height);
                
                // Resize the image ************************************************************
                $current_width = $crop_width;
                $current_height = $crop_height; 
                $canvas = imagecreatetruecolor(96, 72); 
                imagecopyresampled($canvas, $croppedimage, 0, 0, 0, 0, 96, 72, $current_width, $current_height);
                
                ob_start();
                switch ($file->type) {
                    case 'image/jpeg':
                        imagejpeg($canvas);
                        break;
                    case 'image/png':
                        imagepng($canvas);
                        break;
                    case 'image/gif':
                        imagegif($canvas);
                        break;
                }
                $newfile->size = ob_get_length();
                $newfile->data = ob_get_clean();
                imagedestroy($canvas);
                
                // Save the thumbnail **********************************************************
                // Clear the id so the database will increment it
                $newfile->id = NULL;
                $newfile->use_for = 'thumbnail';                
                $newfile->save();
                $story->addFile($newfile);
          
                header('Location: ?view=thanks&_type=story');
                exit();
                break;
            case 'deletenewsletter':
                if (!($newsletter = UNL_ENews_Newsletter::getByID($_POST['newsletter_id']))) {
                    throw new Exception('Invalid newsletter selected for delete');
                }
                if (UNL_ENews_Controller::getUser(true)->hasPermission($newsletter->newsroom_id)) {
                    $newsletter->delete();
                }
                break;
        }
    }
    
    function filterPostValues()
    {
        unset($_POST['uid']);
        unset($_POST['id']);
    }
    
    public static function getURL()
    {
        return self::$url;
    }
    
    function run()
    {
         if (isset($this->view_map[$this->options['view']])) {
         //    $this->options['controller'] = $this;
             $this->actionable[] = new $this->view_map[$this->options['view']]($this->options);
         } else {
             throw new Exception('Un-registered view');
         }
    }
    
    public static function setObjectFromArray(&$object, $values)
    {
        foreach (get_object_vars($object) as $key=>$default_value) {
            if (isset($values[$key]) && !empty($values[$key])) {
                $object->$key = $values[$key]; 
            }
        }
    }
    
    /**
     * 
     * @return mysqli
     */
    public static function getDB()
    {
        $mysqli = new mysqli('localhost', self::$db_user, self::$db_pass, 'enews');
        if (mysqli_connect_error()) {
            throw new Exception('Database connection error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        return $mysqli;
    }
    
    public static function isAdmin($uid)
    {
        if (in_array($uid, self::$admins)) {
            return true;
        }
        
        return false;
    }
}

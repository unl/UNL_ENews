<?php
class UNL_ENews_Controller
{
    /**
     * Options array
     * Will include $_GET vars, this is the newsroom being used across views
     */
    public $options = array('view' => 'submit', 'format' => 'html', 'newsroom' => '1');

    /**
     * A map of views to models
     *
     * @var array(view=>CLASSNAME)
     */
    protected $view_map = array('newsletter'  => 'UNL_ENews_Newsletter_Public',
                                'latest'      => 'UNL_ENews_StoryList_Latest',
                                'mynews'      => 'UNL_ENews_User_StoryList',
                                'story'       => 'UNL_ENews_Story',
                                'submit'      => 'UNL_ENews_Submission',
                                'thanks'      => 'UNL_ENews_Confirmation',
                                'manager'     => 'UNL_ENews_Manager',
                                'file'        => 'UNL_ENews_File',
                                'preview'     => 'UNL_ENews_Newsletter_Preview',
                                'previewStory' => 'UNL_ENews_Newsletter_Preview_Story',
                                'presentationList' => 'UNL_ENews_PresentationLister',
                                'newsletters' => 'UNL_ENews_Newsroom_Newsletters',
                                'sendnews'    => 'UNL_ENews_EmailDistributor',
                                'help'        => 'UNL_ENews_Help',
                                'newsroom'    => 'UNL_ENews_Newsroom',
                                'newsletterStory'    => 'UNL_ENews_Newsletter_Story',
                                'unpublishedStories' => 'UNL_ENews_Newsroom_UnpublishedStories',
                                'gastats' => 'UNL_ENews_GAStats'
    
    );

    public static $pagetitle = array('latest'      => 'Latest News',
                                     'mynews'      => 'Your News Submissions',
                                     'submit'      => 'Submit an Item',
                                     'manager'     => 'Manage News',
                                     'preview'     => 'Build Newsletter',
                                     'newsletters' => 'Newsletters',
                                     'help'        => 'Help! How do I&hellip;',
    );

    protected static $auth;

    protected static $admins = array('admin');

    /**
     * The currently logged in user.
     *
     * @var UNL_ENews_User
     */
    protected static $user = false;

    public static $url = '';

    protected static $db_settings = array();

    public $actionable = array();

    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->authenticate(true);

        try {
            if (!empty($_POST)) {
                $this->handlePost();
            }
            $this->run();
        } catch(Exception $e) {
            if (isset($this->options['ajaxupload'])) {
                echo $e->getMessage();
                exit();
            }

            if (false == headers_sent()
                && $code = $e->getCode()) {
                header('HTTP/1.1 '.$code.' '.$e->getMessage());
                header('Status: '.$code.' '.$e->getMessage());
            }

            $this->actionable[] = $e;
        }
    }

    public static function setDbSettings($settings = array())
    {
        $settings = $settings + self::$db_settings;
        if (empty($settings['host'])) {
            $settings['host'] = '127.0.0.1';
        }
        if (empty($settings['user'])) {
            $settings['user'] = 'enews';
        }
        if (empty($settings['password'])) {
            $settings['password'] = 'enews';
        }
        if (empty($settings['dbname'])) {
            $settings['dbname'] = 'enews';
        }

        self::$db_settings = $settings;
    }

    public static function getDbSettings()
    {
        if (empty(self::$db_settings)) {
            self::setDbSettings();
        }

        return self::$db_settings;
    }

    /**
     * Set a list of site admin uids
     *
     * @param array $admins Array of UIDs
     */
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
        } elseif (self::isLoggedIn()) {
            self::$user = UNL_ENews_User::getByUID(self::$auth->getUser());
        }

        return self::$user;
    }

    public static function isLoggedIn()
    {
        if (self::$auth === null) {
            self::$auth = UNL_Auth::factory('SimpleCAS');
        }
        return self::$auth->isLoggedIn();
    }

    /**
     * Set the currently logged in user
     *
     * @return UNL_ENews_User
     */
    public static function setUser(UNL_ENews_User $user)
    {
        self::$user = $user;
    }

    /**
     * Handle data that is POST'ed to the controller.
     *
     * @return void
     */
    function handlePost()
    {
        $this->filterPostValues();
        if (!isset($_POST['_type'])) {
            // Nothing to do here
            return;
        }
        switch($_POST['_type']) {
            case 'story':
                if (!empty($_POST['storyid'])) {
                    if (!($story = UNL_ENews_Story::getByID($_POST['storyid']))) {
                        throw new Exception('The story could not be retrieved');
                    }
                    if (!$story->userCanEdit(UNL_ENews_Controller::getUser(true))) {
                        throw new Exception('You cannot edit that story.');
                    }
                } else {
                    $story = new UNL_ENews_Story;
                }
                self::setObjectFromArray($story, $_POST);

                if (!$story->save()) {
                    throw new Exception('Could not save the story');
                }

                foreach ($_POST['newsroom_id'] as $id) {
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

                //If image was uploaded but a crop area was never selected, make a default thumbnail
                if ($file = $story->getFileByUse('originalimage')) {
                    if (!empty($_POST['originalimage_description'])) {
                        $file->description = $_POST['originalimage_description'];
                        $file->save();
                    }
                    if (!$story->getThumbnail()) {
                        $thumb = $file->saveThumbnail(-1);
                        $story->addFile($thumb);
                    }
                }

                if (isset($_POST['ajaxupload'])) {
                    echo $story->id;
                    exit();
                }

                self::redirect(self::getURL().'?view=thanks&_type='.$_POST['_type']);
            case 'file':
                if ($_FILES['image']['error'] != UPLOAD_ERR_OK) {
                    throw new Exception("Error Uploading File!");
                }

                if (!$story = UNL_ENews_Story::getByID((int)$_POST['storyid'])) {
                    throw new Exception('Cannot get story ('.(int)$_POST['storyid'].') to add file for!');
                }

                if (!$story->userCanEdit(self::getUser(true))) {
                    throw new Exception('Cannot add files to stories you cannot edit');
                }

                $file = new UNL_ENews_File;

                $file_data         = $_FILES['image'];
                $file_data['data'] = file_get_contents($_FILES['image']['tmp_name']);

                //$file_data['description'] = $_POST['description'];


                self::setObjectFromArray($file, $file_data);

                if (isset($this->options['ajaxupload'])) {
                    if (!UNL_ENews_File::validFileName($_FILES['image']['name'])) {
                        throw new Exception('Please Upload an Image in .jpg .png or .gif format.');
                    }
                    $file->use_for = 'originalimage';
                }

                if (!$file->save()) {
                    throw new Exception('Error saving the file');
                }

                $story->addFile($file);

                if (!isset($this->options['ajaxupload'])) {
                    self::redirect(self::getURL().'?view=thanks&_type='.$_POST['_type']);
                }

                // We're doing the ajax upload in step 3 of the submission form, so delete the previous photo
                foreach ($story->getFiles() as $curfile) {
                    if (preg_match('/^image/', $curfile->type)) {
                        //Check to see that we Don't Delete the File we just uploaded
                        if ($curfile->id != $file->id) {
                            $story->removeFile($curfile);
                            $curfile->delete();
                        }
                    }
                }
                // Return the id as the response
                echo $file->id;
                exit();
            case 'thumbnail':
                if (!($story = UNL_ENews_Story::getByID((int)$_POST['storyid']))) {
                    throw new Exception('Could not find that story!');
                }

                if (!$story->userCanEdit(self::getUser(true))) {
                    throw new Exception('Cannot add thumbnail to stories you cannot edit');
                }

                // Get the original
                $file = $story->getFileByUse('originalimage');
                if (false === $file) {
                    throw new Exception('Cannot create a thumbnail for an image that does not exist');
                }

                if ($thumb = $story->getThumbnail()) {
                    //Delete existing thumbnail
                    $story->removeFile($thumb);
                    $thumb->delete();
                }

                // make a new thumbnail
                $thumb = $file->saveThumbnail($_POST['x1'], $_POST['x2'], $_POST['y1'], $_POST['y2']);
                $story->addFile($thumb);

                if (isset($_POST['ajaxupload'])) {
                    echo $thumb->id;
                    exit();
                }

                self::redirect(self::getURL().'?view=thanks&_type=thumbnail');
            case 'deletenewsletter':
                if (!($newsletter = UNL_ENews_Newsletter::getByID($_POST['newsletter_id']))) {
                    throw new Exception('Invalid newsletter selected for delete');
                }
                if (UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($newsletter->newsroom_id)) {
                    $newsletter->delete();
                }
                break;
        }
    }

    /**
     * Filter any pre-populated POST fields to prevent their use.
     *
     * @return void
     */
    function filterPostValues()
    {
        unset($_POST['uid']);
        unset($_POST['id']);
    }

    /**
     * Get the URL to the main site.
     *
     * @return string The URL to the site
     */
    public static function getURL()
    {
        return self::$url;
    }

    /**
     * Populate the actionable items according to the view map.
     *
     * @throws Exception if view is unregistered
     */
    function run()
    {
         if (isset($this->view_map[$this->options['view']])) {
             $this->actionable[] = new $this->view_map[$this->options['view']]($this->options);
         } else {
             throw new Exception('Un-registered view');
         }
    }

    /**
     * Set the public properties for an object with the values in an associative array
     *
     * @param mixed &$object The object to set, usually a UNL_ENews_Record
     * @param array $values  Associtive array of key=>value
     * @throws Exception
     *
     * @return void
     */
    public static function setObjectFromArray(&$object, $values)
    {
        if (!isset($object)) {
            throw new Exception('No object passed!');
        }
        foreach (get_object_vars($object) as $key=>$default_value) {
            if (isset($values[$key]) && !empty($values[$key])) {
                $object->$key = $values[$key];
            }
        }
    }

    /**
     * Converts text urls into clickable links
     *
     * @param $string
     *
     * @return string
     */
    public static function makeClickableLinks($string)
    {
        //make sure there is an http:// on all URLs
        $string = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2",$string);
        // make all URLs links
        $string = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a href=\"$1\">$1</a>",$string);
        // make all emails links
        $string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>",$string);

        return $string;
    }

    /**
     * Connect to the database and return it
     *
     * @return mysqli
     */
    public static function getDB()
    {
        $settings = self::getDbSettings();
        $db = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['dbname']);
        if (mysqli_connect_error()) {
            throw new Exception('Database connection error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        $db->set_charset('utf8');
        return $db;
    }

    /**
     * Check if the user is a site admin or not.
     *
     * @param string $uid The uid to check
     */
    public static function isAdmin($uid)
    {
        if (in_array((string)$uid, self::$admins)) {
            return true;
        }

        return false;
    }

    static function redirect($url, $exit = true)
    {
        header('Location: '.$url);
        if (false !== $exit) {
            exit($exit);
        }
    }

    static function setReplacementData($field, $data)
    {
        switch($field) {
            case 'pagetitle':
                self::$pagetitle['dynamic'] = $data;
                break;
        }
    }

    public function postRun($data)
    {
        if (isset(self::$pagetitle['dynamic'])) {
            $data = str_replace('<title>UNL | Announce </title>',
                                '<title>UNL | Announce | '.self::$pagetitle['dynamic'].'</title>',
                                $data);
        }
        return $data;
    }
}

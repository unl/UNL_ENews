<?php
class UNL_ENews_Controller
{
	/**
	 * Options array
	 * Will include $_GET vars, this is the newsroom being used across views 
	 */
    public $options = array('view' => 'latest', 'format' => 'html', 'newsroom' => '1');
    
    protected $view_map = array('latest'      => 'UNL_ENews_StoryList_Latest',
                                'story'       => 'UNL_ENews_Story',
                                'submit'      => 'UNL_ENews_Submission',
                                'thanks'      => 'UNL_ENews_Confirmation',
                                'manager'     => 'UNL_ENews_Manager',
                                'file'        => 'UNL_ENews_File',
                                'newsletter'  => 'UNL_ENews_Newsletter_Preview',
                                'newsletters' => 'UNL_ENews_Newsroom_Newsletters',
                                'sendnews'    => 'UNL_ENews_EmailDistributor'
    );
    
    public static $pagetitle = array('latest'		=> 'Latest News',
    								 'submit'		=> 'Submit an Item',
                                	 'manager'     	=> 'Manage News',
                               		 'newsletter'  	=> 'Build Newsletter',
                               		 'newsletters' 	=> 'Newsletters',
    );
    
    protected static $auth;
    
    protected static $admins = array('bbieber2', // Brett Bieber
    								 'erasmussen2' //Eric Rasmussen
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
        //$this->authenticate();
        
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
    static function authenticate()
    {
        self::$auth = UNL_Auth::factory('SimpleCAS');
        if (isset($_GET['logout'])) {
            self::$auth->logout();
        } else {
            self::$auth->login();
        }
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
                $object = new $class();
                self::setObjectFromArray($object, $_POST);
                
                if (!$object->save()) {
                    throw new Exception('Could not save the story');
                }
                
                if (isset($_FILES['image'])
                    && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $file_data = $_FILES['image'];
                    $file_data['data'] = file_get_contents($_FILES['image']['tmp_name']);
                    $file = new UNL_ENews_File();
                    self::setObjectFromArray($file, $file_data);
                    if ($file->save()) {
                        $object->addFile($file);
                    } else {
                        throw new Exception('Error saving the file');
                    }
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
                
                header('Location: ?view=thanks&_type='.$_POST['_type']);
                exit();
                break;
            case 'file':
                $class = $this->view_map[$_POST['_type']];
                if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $file_data = $_FILES['image'];
                    $file_data['data'] = file_get_contents($_FILES['image']['tmp_name']);
                    $object = new $class();
                    self::setObjectFromArray($object, $file_data);
                    $object->save();
                    header('Location: ?view=file&id='.$object->id);
                    exit();
                }
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

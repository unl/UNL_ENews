<?php
class UNL_ENews_Controller
{
    public $options = array('view' => 'latest');
    
    protected $view_map = array('latest' => 'UNL_ENews_Latest',
                                'story'  => 'UNL_ENews_Story',
                                'submit' => 'UNL_ENews_Submission',
                                'thanks' => 'UNL_ENews_Confirmation'
    );
    
    protected static $auth;
    
    protected static $admins = array('bbieber2', // Brett Bieber
        );
        
    public static $url = '';
    
    /**
     * The currently logged in user.
     * 
     * @var UNL_UCARE_Applicant
     */
    protected static $user;
    
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
        $this->run();
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
        self::$user = new UNL_ENews_User(array('uid'=>self::$auth->getUser()));
    }
    
    /**
     * get the currently logged in user
     * 
     * @return UNL_ENews_User
     */
    public static function getUser()
    {
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
                $object->save();
                // save the data
                header('Location: ?view=thanks&_type='.$_POST['_type']);
        }
        throw new Exception('Invalid data submitted.');
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
             $this->options['controller'] = $this;
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
        return new mysqli('localhost', self::$db_user, self::$db_pass, 'enews');
    }
    
    public static function isAdmin($uid)
    {
        if (in_array($uid, self::$admins)) {
            return true;
        }
        
        return false;
    }
}
?>
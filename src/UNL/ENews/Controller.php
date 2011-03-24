<?php
class UNL_ENews_Controller
{
    /**
     * Options array
     * Will include $_GET vars
     */
    public $options = array('view' => 'submit', 'format' => 'html');

    /**
     * A map of views to models
     *
     * @var array(view=>CLASSNAME)
     */
    protected $view_map = array('newsletter'         => 'UNL_ENews_Newsletter_Public',
                                'latest'             => 'UNL_ENews_StoryList_Latest',
                                'mynews'             => 'UNL_ENews_User_StoryList',
                                'story'              => 'UNL_ENews_Story',
                                'submit'             => 'UNL_ENews_Submission',
                                'thanks'             => 'UNL_ENews_Confirmation',
                                'manager'            => 'UNL_ENews_Manager',
                                'file'               => 'UNL_ENews_File',
                                'preview'            => 'UNL_ENews_Newsletter_Preview',
                                'previewStory'       => 'UNL_ENews_Newsletter_Preview_Story',
                                'presentationList'   => 'UNL_ENews_PresentationLister',
                                'newsletters'        => 'UNL_ENews_Newsroom_Newsletters',
                                'sendnews'           => 'UNL_ENews_EmailDistributor',
                                'help'               => 'UNL_ENews_Help',
                                'newsroom'           => 'UNL_ENews_Newsroom_EditForm',
                                'newsletterStory'    => 'UNL_ENews_Newsletter_Story',
                                'unpublishedStories' => 'UNL_ENews_Newsroom_UnpublishedStories',
                                'gastats'            => 'UNL_ENews_GAStats',
                                'archive'            => 'UNL_ENews_Archive',
    );

    public static $pagetitle = array('latest'      => 'Latest News',
                                     'mynews'      => 'Your News Submissions',
                                     'submit'      => 'Submit an Item',
                                     'manager'     => 'Manage News',
                                     'preview'     => 'Build Newsletter',
                                     'newsletters' => 'Newsletters',
                                     'help'        => 'Help! How do I&hellip;',
    );

    public static $sitetitle;

    protected static $auth;

    protected static $admins = array('admin');

    /**
     * The currently logged in user.
     *
     * @var UNL_ENews_User
     */
    protected static $user = false;

    public static $url = '';

    protected static $db_settings = array(
        'host'     => 'localhost',
        'user'     => 'enews',
        'password' => 'enews',
        'dbname'   => 'enews'
    );

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
        self::$db_settings = $settings + self::$db_settings;
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

        return self::$user;
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
        $postHandler = new UNL_ENews_PostHandler($this->options, $_POST, $_FILES);
        return $postHandler->handle();
    }

    /**
     * Get the main URL for this instance or an individual object
     *
     * @param mixed $mixed             An object to retrieve the URL to
     * @param array $additional_params Querystring params to add
     *
     * @return string
     */
    public static function getURL($mixed = null, $additional_params = array())
    {

        $url = self::$url;

        if (is_object($mixed)) {
            switch (get_class($mixed)) {
            default:

            }
        }

        return self::addURLParams($url, $additional_params);
    }

    /**
     * Add unique querystring parameters to a URL
     *
     * @param string $url               The URL
     * @param array  $additional_params Additional querystring parameters to add
     *
     * @return string
     */
    public static function addURLParams($url, $additional_params = array())
    {
        $params = array();
        if (strpos($url, '?') !== false) {
            list($url, $existing_params) = explode('?', $url);
            $existing_params = explode('&', $existing_params);
            foreach ($existing_params as $val) {
                list($var, $val) = explode('=', $val);
                $params[$var] = $val;
            }
        }

        $params = array_merge($params, $additional_params);

        $url .= '?';

        foreach ($params as $option=>$value) {
            if ($option == 'driver') {
                continue;
            }
            if ($option == 'format'
                && $value = 'html') {
                continue;
            }
            if (!empty($value)) {
                $url .= "&$option=$value";
            }
        }
        $url = str_replace('?&', '?', $url);
        return trim($url, '?;=');
    }

    /**
     * Populate the actionable items according to the view map.
     *
     * @throws Exception if view is unregistered
     */
    function run()
    {
         if (!isset($this->view_map[$this->options['view']])) {
             throw new Exception('Un-registered view');
         }
         $this->actionable[] = new $this->view_map[$this->options['view']]($this->options);
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
        static $db = false;
        if (!$db) {
            $settings = self::getDbSettings();
            $db = new mysqli($settings['host'], $settings['user'], $settings['password'], $settings['dbname']);
            if ($db->connect_error) {
                die('Connect Error (' . $db->connect_errno . ') '
                        . $db->connect_error);
            }
            $db->set_charset('utf8');
        }
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
        if (!defined('CLI')
            && false !== $exit) {
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
    	if (isset(self::$sitetitle)) {
            $data = str_replace('<h1>UNL Announce</h1>',
                                '<h1>'.self::$sitetitle.'</h1>',
                                $data);
        }
        return $data;
    }
}

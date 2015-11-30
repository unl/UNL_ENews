<?php
class UNL_ENews_Controller
{
    /**
     * Options array
     * Will include $_GET vars
     */
    public $options = array('model' => 'UNL_ENews_Submission', 'format' => 'html');

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

    /**
     * See http://htmlpurifier.org/live/configdoc/plain.html#HTML.Allowed
     * Maps to the htmlpurifier config option HTML.Allowed
     * 
     * @var string
     */
    public static $allowed_html_field_description = 'a[href],strong,p,em';

    /**
     * See http://htmlpurifier.org/live/configdoc/plain.html#HTML.Allowed
     * Maps to the htmlpurifier config option HTML.Allowed
     * 
     * @var string
     */
    public static $allowed_html_field_full_article = 'a[href],strong,p,ul,ol,li,em';

    /**
     * See https://github.com/cure53/DOMPurify#can-i-configure-it
     * Maps to the DOMPurify config option `ALLOWED_TAGS`
     * 
     * @var array
     */
    public static $js_allowed_tags_description = array('a','strong','p','em');

    /**
     * See https://github.com/cure53/DOMPurify#can-i-configure-it
     * Maps to the DOMPurify config option `ALLOWED_ATTR`
     * 
     * @var array
     */
    public static $js_allowed_attr_description = array('href');

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
            throw new Exception('You must log in to view this resource!', 401);
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
                && $value == 'html') {
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
         if (!isset($this->options['model'])
             || false === $this->options['model']) {
             throw new Exception('Un-registered view', 404);
         }
         $this->actionable[] = new $this->options['model']($this->options);
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
        
        // make all emails links (not done with HTML Purifier)
        $string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?))/i","<a href=\"mailto:$1\">$1</a>",$string);

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

    /**
     * Format a date in the short/condensed format preferred by Kelly/Troy.
     * 
     * In particular this uses AP style month abbreviations.
     *
     * @param string $date Date in MySQL date format.
     */
    public static function formatDate($date)
    {
        $time = strtotime($date);
        switch(date('n', $time)) {
            case 1:
                $month = 'Jan.';
                break;
            case 2:
                $month = 'Feb.';
                break;
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
                $month = date('F', $time);
                break;
            case 8:
                $month = 'Aug.';
                break;
            case 9:
                $month = 'Sept.';
                break;
            case 10:
                $month = 'Oct.';
                break;
            case 11:
                $month = 'Nov.';
                break;
            case 12:
                $month = 'Dec.';
                break;
        }
        return date('D. ', $time).$month.date(' d, Y', $time);
    }
}

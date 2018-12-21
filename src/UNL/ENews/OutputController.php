<?php

class UNL_ENews_OutputController extends Savvy_Turbo
{
    protected $theme = 'MockU';

    private $scriptDeclarations = array();
    private $scripts = array();

    function __construct($options = array())
    {
        parent::__construct();
        Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_';
        $this->addFilters(array('UNL_ENews_PostRunFilter', 'postRun'));
    }

    /**
     * Set a specific theme for this instance
     * 
     * @param string $theme Theme name, which corresponds to a directory in www/
     * 
     * @throws Exception
     */
    function setTheme($theme)
    {
        if (!is_dir(dirname(dirname(dirname(__DIR__))) . '/www/themes/'.$theme)) {
            throw new Exception('Invalid theme, there are no files in '.$dir);
        }
        $this->theme = $theme;
    }

    /**
     * Set the array of template paths necessary for this format
     * 
     * @param string $format Format to use
     */
    function setTemplateFormatPaths($format)
    {
        $web_dir = dirname(dirname(dirname(__DIR__))) . '/www';

        $this->setTemplatePath(
            array(
                $web_dir . '/templates/' . $format,
                $web_dir . '/themes/' . $this->theme . '/' . $format
            )
        );
    }

    /**
     * Add a template path for default and theme
     * 
     * @param string $format Format to use
     */
    function addTemplateFormatPaths($format)
    {
        $web_dir = dirname(dirname(dirname(__DIR__))) . '/www';

        $this->addTemplatePath(
            array(
                $web_dir . '/templates/' . $format,
                $web_dir . '/themes/' . $this->theme . '/' . $format
            )
        );
    }

    /**
     * 
     * @param timestamp $expires timestamp
     * 
     * @return void
     */
    function sendCORSHeaders($expires = null)
    {
        // Specify domains from which requests are allowed
        header('Access-Control-Allow-Origin: *');

        // Specify which request methods are allowed
        header('Access-Control-Allow-Methods: GET, OPTIONS');

        // Additional headers which may be sent along with the CORS request
        // The X-Requested-With header allows jQuery requests to go through

        header('Access-Control-Allow-Headers: X-Requested-With');

        // Set the ages for the access-control header to 20 days to improve speed/caching.
        header('Access-Control-Max-Age: 1728000');

        if (isset($expires)) {
            // Set expires header for 24 hours to improve speed caching.
            header('Expires: '.date('r', $expires));
        }
    }

    /**
     *  Load a script tag declaration to be applied to page later
     *
     * @param string $content Content of script tag
     * @param string $type Type of script tag
     * @param boolean $appendToHead whether to append tag to head
     *
     * @return void
     */
    function loadScriptDeclaration($content, $type = '', $appendToHead = FALSE) {
        $this->scriptDeclarations[] = new ScriptDeclaration($content, $type, $appendToHead);
    }

    /**
     *  Apply loaded script tags to page
     *
     * @param object $page Page to apply loaded script tags
     *
     * @return void
     */
    function applyScriptDeclarations(&$page) {
        foreach ($this->scriptDeclarations as $declaration){
            if ($declaration instanceof ScriptDeclaration) {
                $page->addScriptDeclaration($declaration->content(), $declaration->type() , $declaration->appendToHead());
            }
        }
    }

    /**
     *  Load a script file to be applied to page later
     *
     * @param string $url URL of script
     * @param string $type Type of script tag
     * @param boolean $appendToHead whether to append tag to head
     *
     * @return void
     */
    function loadScript($url, $type = '', $appendToHead = FALSE) {
        $this->scripts[] = new Script($url, $type, $appendToHead);
    }

    /**
     *  Apply loaded scripts to page
     *
     * @param object $page Page to apply loaded scripts
     *
     * @return void
     */
    function applyScripts(&$page) {
        foreach ($this->scripts as $script){
            if ($script instanceof Script) {
                $page->addScript($script->url(), $script->type() , $script->appendToHead());
            }
        }
    }
}

class ScriptDeclaration {
    private $content;
    private $type;
    private $appendToHead;

    public function __construct($content, $type = '', $appendToHead = FALSE)
    {
        $this->content = $content;
        $this->type = $type;
        $this->appendToHead = ($appendToHead === TRUE);
    }

    public function content() {
        return $this->content;
    }

    public function type() {
        return $this->type;
    }

    public function appendToHead() {
        return $this->appendToHead;
    }
}

class Script {
    private $url;
    private $type;
    private $appendToHead;

    public function __construct($url, $type = '', $appendToHead = FALSE)
    {
        $this->url = $url;
        $this->type = $type;
        $this->appendToHead = ($appendToHead === TRUE);
    }

    public function url() {
        return $this->url;
    }

    public function type() {
        return $this->type;
    }

    public function appendToHead() {
        return $this->appendToHead;
    }
}


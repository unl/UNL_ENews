<?php

class UNL_ENews_OutputController extends Savvy_Turbo
{
    protected $theme = 'MockU';

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
}


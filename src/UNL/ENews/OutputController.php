<?php

class UNL_ENews_OutputController extends Savvy_Turbo
{
    
    function __construct($options = array())
    {
        parent::__construct();
        Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_';
        $this->setTemplatePath(dirname(dirname(dirname(dirname(__FILE__)))).'/www/templates/default');
        $this->addFilters(array('UNL_ENews_Controller', 'postRun'));
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


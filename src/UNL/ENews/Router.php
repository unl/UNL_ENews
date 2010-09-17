<?php
// This is used to parse the CLI options and set up the search parameters
class UNL_ENews_Router
{
    public static function route($requestURI)
    {
    if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, strlen($_SERVER['QUERY_STRING'])*-1-1);
             	
        }

        $base = preg_quote(UNL_ENews_Controller::getURL(), '/');
        $requestURI = "http://localhost" . $requestURI;
       
        $options = array();
        switch(true) {
        	//For calling a story.  url = www/newsletterShortName/newsletterID/storyID/
            case preg_match('/'.$base.'((?:[a-z][a-z]+))(\\/)(\\d+)(\\/)(\\d+)/is', $requestURI, $matches):
                $options['view']         = 'story';
                $options['newsName'] 	 = $matches[1];
                $options['newsID']       = $matches[3];
                $options['id']           = $matches[5];
                break;
        }
        return $options;
    }
}
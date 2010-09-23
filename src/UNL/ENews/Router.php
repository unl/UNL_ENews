<?php
class UNL_ENews_Router
{
    public static function route($requestURI)
    {
    if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, strlen($_SERVER['QUERY_STRING'])*-1-1);
        }

        $base = preg_quote(UNL_ENews_Controller::getURL(), '/');
        $requestURI = "http://ucommnaber.unl.edu" . $requestURI;

        $options = array();
        switch(true) {
            //For calling a story.  url = www/newsRoomShortName/newsletterID/storyID/
            case preg_match('/'.$base.'((?:[a-z][a-z]+))(\\/)(\\d+)(\\/)(\\d+)/is', $requestURI, $matches):
                $options['view']         = 'newsletterStory';
                $options['newsName'] 	 = $matches[1];
                $options['newsID']       = $matches[3];
                $options['id']           = $matches[5];	
                break;
            //For calling a newsletter.  url = www/newsRoomShortName/newsletterID/
            case preg_match('/'.$base.'((?:[a-z][a-z]+))(\\/)(\\d+)/is', $requestURI, $matches):
                $options['view']         = 'newsletter';
                $options['newsName']     = $matches[1];
                $options['id']           = $matches[3];
                break;
            //For submiting to a news letter.  url = www/newsRoomShortName/submit
            case preg_match('/'.$base.'((?:[a-z][a-z]+))(\\/)('.'submit'.')/is', $requestURI, $matches):
                $options['view']         = 'submit';
                $options['shortname']    = $matches[1];
                break;
            default:
                //Check to see if the URI is a clean link.  if it is, call a bad view.
                if(preg_match('/'.$base.'(([\\w\\.\\-]+)+)/is', $requestURI, $matches)){
                    $options['view'] = 'Bad Cleanlink';
                }
        }
        return $options;
    }
}
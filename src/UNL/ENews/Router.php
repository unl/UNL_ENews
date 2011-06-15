<?php
class UNL_ENews_Router
{
    public static function route($requestURI)
    {

        $base = UNL_ENews_Controller::getURL();

        if (filter_var($base, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
            $base = parse_url($base, PHP_URL_PATH);
        }

        $quotedBase = preg_quote($base, '/');

        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen(urldecode($_SERVER['QUERY_STRING'])) - 1);
        }

        $options = array();

        switch(true) {
            // Files associated with tstories
            case preg_match('/'.$quotedBase.'file([\d]+)\.(jpg|png|gif)$/', $requestURI, $matches):
                $options['view']         = 'file';
                $options['id']           = $matches[1];
                $options['content-type'] = $matches[2];
                break;
            //For calling a story.  url = www/newsRoomShortName/newsletterID/storyID/
            case preg_match('/'.$quotedBase.'([a-z]+)\/([\d]+)\/([\d]+)$/i', $requestURI, $matches):
                $options['view']          = 'newsletterStory';
                $options['shortname']     = $matches[1];
                $options['newsletter_id'] = $matches[2];
                $options['id']            = $matches[3];
                break;
            //For calling a newsletter.  url = www/newsRoomShortName/newsletterID/
            case preg_match('/'.$quotedBase.'([a-z]+)\/([\d]+)\/?$/i', $requestURI, $matches):
                $options['view']         = 'newsletter';
                $options['shortname']    = $matches[1];
                $options['id']           = $matches[2];
                break;
            //For submiting to a news letter.  url = www/newsRoomShortName/submit
            case preg_match('/'.$quotedBase.'([a-z]+)\/submit$/i', $requestURI, $matches):
                $options['view']         = 'submit';
                $options['shortname']    = $matches[1];
                break;
            // For managing a newsroom.
            case preg_match('/'.$quotedBase.'([a-z]+)\/manage$/i', $requestURI, $matches):
                $options['view']         = 'manager';
                $options['shortname']    = $matches[1];
                break;
            //For viewing the newest newsletter for a newsroom.
            case preg_match('/'.$quotedBase.'([a-z]+)\/?$/i', $requestURI, $matches):
                $options['view']         = 'newsletter';
                $options['shortname']    = $matches[1];
                break;
            //For viewing an archive.  url = www/newsRoomShortName/archive
            case preg_match('/'.$quotedBase.'([a-z]+)\/archive$/i', $requestURI, $matches):
                $options['view']         = 'archive';
                $options['shortname']    = $matches[1];
                break;
            // Default view
            case ($requestURI == $base):
                break;
            default:
                // No registered view matches.
                $options['view'] = '404, missing view';
        }
        return $options;
    }
}
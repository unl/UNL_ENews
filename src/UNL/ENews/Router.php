<?php
class UNL_ENews_Router
{
    public static function route($requestURI)
    {

        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr($requestURI, 0, -strlen(urldecode($_SERVER['QUERY_STRING'])) - 1);
        }

        // Trim the base part of the URL
        $requestURI = substr($requestURI, strlen(parse_url(UNL_ENews_Controller::getURL(), PHP_URL_PATH)));
        $options = array();


        if (empty($requestURI)) {
            // Default view/homepage
            return $options;
        }

        switch(true) {
            // Files associated with tstories
            case preg_match('/^file([\d]+)\.(jpg|png|gif)$/', $requestURI, $matches):
                $options['view']         = 'file';
                $options['id']           = $matches[1];
                $options['content-type'] = $matches[2];
                break;

            // Story direct URL, no newsroom association
            case preg_match('/^stories\/([\d]+)$/', $requestURI, $matches):
                $options['view']         = 'story';
                $options['id']           = $matches[1];
                break;

            //For calling a story.  url = www/newsRoomShortName/newsletterID/storyID/summary
            case preg_match('/^([a-z]+)\/([\d]+)\/([\d]+)\/summary$/i', $requestURI, $matches):
                $options['view']          = 'storySummary';
                $options['shortname']     = $matches[1];
                $options['newsletter_id'] = $matches[2];
                $options['id']            = $matches[3];
                break;
            //For calling a story.  url = www/newsRoomShortName/newsletterID/storyID/
            case preg_match('/^([a-z]+)\/([\d]+)\/([\d]+)$/i', $requestURI, $matches):
                $options['view']          = 'newsletterStory';
                $options['shortname']     = $matches[1];
                $options['newsletter_id'] = $matches[2];
                $options['id']            = $matches[3];
                break;
            //For calling a newsletter.  url = www/newsRoomShortName/newsletterID/
            case preg_match('/^([a-z]+)\/([\d]+)\/?$/i', $requestURI, $matches):
                $options['view']         = 'newsletter';
                $options['shortname']    = $matches[1];
                $options['id']           = $matches[2];
                break;
            //For submiting to a news letter.  url = www/newsRoomShortName/submit
            case preg_match('/^([a-z]+)\/submit$/i', $requestURI, $matches):
                $options['view']         = 'submit';
                $options['shortname']    = $matches[1];
                break;
            // For managing a newsroom.
            case preg_match('/^([a-z]+)\/manage$/i', $requestURI, $matches):
                $options['view']         = 'manager';
                $options['shortname']    = $matches[1];
                break;
            // Stories for a newsroom
            case preg_match('/^([a-z]+)\/stories$/i', $requestURI, $matches):
            case preg_match('/^([a-z]+)\/latest$/i', $requestURI, $matches):
                $options['view']         = 'latest';
                $options['shortname']    = $matches[1];
                break;
            //For viewing the newest newsletter for a newsroom.
            case preg_match('/^([a-z]+)\/?$/i', $requestURI, $matches):
                $options['view']         = 'newsletter';
                $options['shortname']    = $matches[1];
                break;
            //For viewing an archive.  url = www/newsRoomShortName/archive
            case preg_match('/^([a-z]+)\/archive$/i', $requestURI, $matches):
                $options['view']         = 'archive';
                $options['shortname']    = $matches[1];
                break;
            default:
                // No registered view matches.
                $options['view'] = '404, missing view';
        }
        return $options;
    }
}
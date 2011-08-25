<?php

$routes = array();

// Files associated with stories
$routes['/^file(?P<id>[\d]+)\.(?P<content_type>jpg|png|gif)$/'] = 'UNL_ENews_File_Cacheable';

// Story direct URL, no newsroom association
$routes['/^stories\/(?P<id>[\d]+)$/'] = 'UNL_ENews_PublishedStory';

//For calling a story.  url = newsRoomShortName/newsletterID/storyID/summary
$routes['/^(?P<shortname>[a-z\-0-9]+)\/(?P<newsletter_id>[\d]+)\/(?P<id>[\d]+)\/summary$/i'] = 'UNL_ENews_Newsletter_Story_Summary';

//For calling a story.  url = newsRoomShortName/newsletterID/storyID/
$routes['/^(?P<shortname>[a-z\-0-9]+)\/(?P<newsletter_id>[\d]+)\/(?P<id>[\d]+)$/i'] = 'UNL_ENews_Newsletter_Story';

//For calling a newsletter.  url = www/newsRoomShortName/newsletterID/
$routes['/^(?P<shortname>[a-z\-0-9]+)\/(?P<id>[\d]+)\/?$/i'] = 'UNL_ENews_Newsletter_Public';

//For submiting to a news letter.  url = www/newsRoomShortName/submit
$routes['/^(?P<shortname>[a-z\-0-9]+)\/submit$/i'] = 'UNL_ENews_Submission';

// For managing a newsroom.
$routes['/^(?P<shortname>[a-z\-0-9]+)\/manage$/i'] = 'UNL_ENews_Manager';

// Stories for a newsroom
$routes['/^(?P<shortname>[a-z\-0-9]+)\/stories$/i'] = 'UNL_ENews_Newsroom_Stories_Published';

// Stories which have current publish date range
$routes['/^(?P<shortname>[a-z\-0-9]+)\/latest$/i']  = 'UNL_ENews_StoryList_Latest';

//For viewing the newest newsletter for a newsroom.
$routes['/^(?P<shortname>[a-z\-0-9]+)\/?$/i'] = 'UNL_ENews_Newsletter_Public';


//For viewing an archive.  url = www/newsRoomShortName/archive
$routes['/^(?P<shortname>[a-z\-0-9]+)\/archive$/i'] = 'UNL_ENews_Archive';

// Now all the ?view= routes
$routes += array(
    'archive'            => 'UNL_ENews_Archive',
    'thanks'             => 'UNL_ENews_Confirmation',
    'sendnews'           => 'UNL_ENews_EmailDistributor',
    'file'               => 'UNL_ENews_File_Cacheable',
    'gastats'            => 'UNL_ENews_GAStats',
    'help'               => 'UNL_ENews_Help',
    'manager'            => 'UNL_ENews_Manager',
    'newsroom'           => 'UNL_ENews_Newsroom_EditForm',
    'newsletters'        => 'UNL_ENews_Newsroom_Newsletters',
    'unpublishedStories' => 'UNL_ENews_Newsroom_UnpublishedStories',
    'preview'            => 'UNL_ENews_Newsletter_Preview',
    'previewStory'       => 'UNL_ENews_Newsletter_Preview_Story',
    'newsletter'         => 'UNL_ENews_Newsletter_Public',
    'newsletterStory'    => 'UNL_ENews_Newsletter_Story',
    'storySummary'       => 'UNL_ENews_Newsletter_Story_Summary',
    'presentationList'   => 'UNL_ENews_PresentationLister',
    'story'              => 'UNL_ENews_PublishedStory',
    'latest'             => 'UNL_ENews_StoryList_Latest',
    'submit'             => 'UNL_ENews_Submission',
    'mynews'             => 'UNL_ENews_User_StoryList',
);

return $routes;

<?php

$routes = array();

$shortname = '(?P<shortname>[a-z\-0-9_]+)';

// Legacy route for files associated with stories, this was used before StaticCache was put in place
$routes['/^file(?P<id>[\d]+)\.(?P<content_type>jpg|png|gif)$/'] = 'UNL_ENews_File_Cacheable';

// Files associated with stories
$routes['/^files\/file(?P<id>[\d]+)\.(?P<content_type>jpg|png|gif)$/'] = 'UNL_ENews_File_Cacheable';

// Story direct URL, no newsroom association
$routes['/^stories\/(?P<id>[\d]+)$/'] = 'UNL_ENews_PublishedStory';

//For calling a story.  url = newsRoomShortName/newsletterID/storyID/summary
$routes['/^' . $shortname . '\/(?P<newsletter_id>[\d]+)\/(?P<id>[\d]+)\/summary$/i'] = 'UNL_ENews_Newsletter_Story_Summary';

//For calling a story.  url = newsRoomShortName/newsletterID/storyID/
$routes['/^' . $shortname . '\/(?P<newsletter_id>[\d]+)\/(?P<id>[\d]+)$/i'] = 'UNL_ENews_Newsletter_Story';

//For calling a newsletter.  url = www/newsRoomShortName/newsletterID/
$routes['/^' . $shortname . '\/(?P<id>[\d]+)\/?$/i'] = 'UNL_ENews_Newsletter_Public';

//For submiting to a news letter.  url = www/newsRoomShortName/submit
$routes['/^' . $shortname . '\/submit$/i'] = 'UNL_ENews_Submission';

// For managing a newsroom.
$routes['/^' . $shortname . '\/manage$/i'] = 'UNL_ENews_Manager';

// For editing newsroom details.
$routes['/^' . $shortname . '\/details$/i'] = 'UNL_ENews_Newsroom_ManageDetails';

// Stories for a newsroom
$routes['/^' . $shortname . '\/stories$/i'] = 'UNL_ENews_Newsroom_Stories_Published';

// Stories which have current publish date range
$routes['/^' . $shortname . '\/latest$/i']  = 'UNL_ENews_StoryList_Latest';

//For viewing the newest newsletter for a newsroom.
$routes['/^' . $shortname . '\/?$/i'] = 'UNL_ENews_Newsletter_Public';

//For viewing an archive.  url = www/newsRoomShortName/archive
$routes['/^' . $shortname . '\/archive$/i'] = 'UNL_ENews_Archive';

// Email open tracking link.
$routes['/^' . $shortname . '\/open\/(?P<id>[\d]+)\/?$/i'] = 'UNL_ENews_Newsletter_Open';

// Now all the ?view= routes
$routes += array(
    'archive'            => 'UNL_ENews_Archive',
    'thanks'             => 'UNL_ENews_Confirmation',
    'sendnews'           => 'UNL_ENews_EmailDistributor',
    'file'               => 'UNL_ENews_File_Cacheable',
    'gastats'            => 'UNL_ENews_GAStats',
    'help'               => 'UNL_ENews_Help',
    'manager'            => 'UNL_ENews_Manager',
    'newsroom'           => 'UNL_ENews_Newsroom_ManageDetails',
    'newsletters'        => 'UNL_ENews_Newsroom_Newsletters',
    'unpublishedStories' => 'UNL_ENews_Newsroom_UnpublishedStories',
    'reusableStories'    => 'UNL_ENews_Newsroom_ReusableStories',
    'open'               => 'UNL_ENews_Newsletter_Open',
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
    'addnewsroom'        => 'UNL_ENews_Admin_AddNewsroom',
);

return $routes;

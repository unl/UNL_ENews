<?php

$routes = array();

// Files associated with stories
$routes['/^file(?<id>[\d]+)\.(?<content_type>jpg|png|gif)$/'] = 'UNL_ENews_File_Cacheable';

// Story direct URL, no newsroom association
$routes['/^stories\/(?<id>[\d]+)$/'] = 'UNL_ENews_PublishedStory';

//For calling a story.  url = newsRoomShortName/newsletterID/storyID/summary
$routes['/^(?<shortname>[a-z]+)\/(?<newsletter_id>[\d]+)\/(?<id>[\d]+)\/summary$/i'] = 'UNL_ENews_Newsletter_Story_Summary';

//For calling a story.  url = newsRoomShortName/newsletterID/storyID/
$routes['/^(?<shortname>[a-z]+)\/(?<newsletter_id>[\d]+)\/(?<id>[\d]+)$/i'] = 'UNL_ENews_Newsletter_Story';

//For calling a newsletter.  url = www/newsRoomShortName/newsletterID/
$routes['/^(?<shortname>[a-z]+)\/(?<id>[\d]+)\/?$/i'] = 'UNL_ENews_Newsletter_Public';

//For submiting to a news letter.  url = www/newsRoomShortName/submit
$routes['/^(?<shortname>[a-z]+)\/submit$/i'] = 'UNL_ENews_Submission';

// For managing a newsroom.
$routes['/^(?<shortname>[a-z]+)\/manage$/i'] = 'UNL_ENews_Manager';

// Stories for a newsroom
$routes['/^(?<shortname>[a-z]+)\/stories$/i'] = 'UNL_ENews_StoryList_Latest';
$routes['/^(?<shortname>[a-z]+)\/latest$/i']  = 'UNL_ENews_StoryList_Latest';

//For viewing the newest newsletter for a newsroom.
$routes['/^(?<shortname>[a-z]+)\/?$/i'] = 'UNL_ENews_Newsletter_Public';


//For viewing an archive.  url = www/newsRoomShortName/archive
$routes['/^(?<shortname>[a-z]+)\/archive$/i'] = 'UNL_ENews_Archive';

// Now all the ?view= routes
$routes += array(
    'thanks'             => 'UNL_ENews_Confirmation',
    'sendnews'           => 'UNL_ENews_EmailDistributor',
    'gastats'            => 'UNL_ENews_GAStats',
    'help'               => 'UNL_ENews_Help',
    'newsroom'           => 'UNL_ENews_Newsroom_EditForm',
    'newsletters'        => 'UNL_ENews_Newsroom_Newsletters',
    'unpublishedStories' => 'UNL_ENews_Newsroom_UnpublishedStories',
    'preview'            => 'UNL_ENews_Newsletter_Preview',
    'previewStory'       => 'UNL_ENews_Newsletter_Preview_Story',
    'presentationList'   => 'UNL_ENews_PresentationLister',
    'mynews'             => 'UNL_ENews_User_StoryList',
    'submit'             => 'UNL_ENews_Submission',
    'manager'            => 'UNL_ENews_Manager',
    'file'               => 'UNL_ENews_File_Cacheable',
);

return $routes;

<?php
$pagetitle = array(
    'UNL_ENews_StoryList_Latest'     => 'Latest News',
    'UNL_ENews_Newsroom_Stories_Published' => 'Published Stories',
    'UNL_ENews_User_StoryList'       => 'Your News Submissions',
    'UNL_ENews_Submission'           => 'Submit an Item',
    'UNL_ENews_Manager'              => 'Manage News',
    'UNL_ENews_Newsletter_Preview'   => 'Build Newsletter',
    'UNL_ENews_Newsroom_Newsletters' => 'Newsletters',
    'UNL_ENews_Help'                 => 'Help! How do I&hellip;',
);

if (isset($pagetitle[$context->options['model']])) {
    echo $pagetitle[$context->options['model']];
}
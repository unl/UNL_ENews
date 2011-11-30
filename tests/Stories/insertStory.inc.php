<?php
$story                  = new UNL_ENews_Story();
$story->title           = "My big news story";
$story->description     = "Here's the basic info about the story";
$story->uid_created     = UNL_ENews_Controller::getUser()->uid;
$story->sponsor         = "Student Affairs";
$story->presentation_id = UNL_ENews_Story_Presentation::getDefault('news')->id;

if (!$story->save()) {
    echo 'Should have saved and it did not';
}
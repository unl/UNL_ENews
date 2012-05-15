<?php
class UNL_ENews_Admin_AddNewsroom extends UNL_ENews_Admin_LoginRequired
{
    function __postConstruct()
    {
        if (!empty($_POST)) {
            $this->handlePost();
        }
    }

    function handlePost()
    {
        switch($_POST['_type']) {
            case 'newsroom':
                $newsroom = new UNL_ENews_Newsroom();
                $newsroom->synchronizeWithArray($_POST);

                if (isset($_POST['allow_submissions'])
                    && $_POST['allow_submissions'] == 'on') {
                    $newsroom->allow_submissions = 1;
                } else {
                    $newsroom->allow_submissions = 0;
                }

                if (empty($newsroom->footer_text)) {
                    $newsroom->footer_text = ' ';
                }

                if (!$newsroom->insert()) {
                    throw new Exception('An error occurred while inserting the newsroom to the DB', 500);
                }

                $user = UNL_ENews_Controller::getUser(true);

                if (!$newsroom->addUser($user)) {
                    throw new Exception('Could not add you to the newsroom', 500);
                }

                $user->newsroom_id = $newsroom->id;
                $user->save();

                UNL_ENews_Controller::redirect($newsroom->getURL() . '/details');
                break;
        }
    }
}
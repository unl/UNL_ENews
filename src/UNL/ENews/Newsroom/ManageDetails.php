<?php
class UNL_ENews_Newsroom_ManageDetails extends UNL_ENews_LoginRequired
{
    public $newsroom;

    function __postConstruct()
    {
        $this->newsroom = UNL_ENews_Newsroom::getById(UNL_ENews_Controller::getUser(true)->newsroom_id);

        if (false === $this->newsroom) {
            throw new UnexpectedValueException('Your newsroom doesn\'t exist!');
        }

        if (!UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($this->newsroom->id)) {
            throw new Exception('You cannot modify a newsroom you don\'t have permission to!', 403);
        }
        
        if (!empty($_POST)) {
            $this->handlePost();
        }
    }

    function handlePost()
    {
        switch($_POST['_type']) {
            case 'removeuser':
            case 'adduser':
                $user = UNL_ENews_User::getByUID($_POST['user_uid']);

                $this->newsroom->{$_POST['_type']}($user);
                UNL_ENews_Controller::redirect(UNL_ENews_Controller::getURL().'?view=newsroom');
                break;
            case 'addemail':
                $optout             = 0;
                $newsletter_default = 0;
                $use_subscribe_link = 0;
                if (isset($_POST['optout'])) {
                    $optout = $_POST['optout'];
                }
                if (isset($_POST['newsletter_default'])) {
                    $newsletter_default = $_POST['newsletter_default'];
                }
                if (isset($_POST['use_subscribe_link'])) {
                    $use_subscribe_link = $_POST['use_subscribe_link'];
                }
                $this->newsroom->{$_POST['_type']}($_POST['email'], $optout, $newsletter_default, $use_subscribe_link);
                UNL_ENews_Controller::redirect(UNL_ENews_Controller::getURL().'?view=newsroom');
                break;
            case 'removeemail':
                $email = UNL_ENews_Newsroom_Email::getById($_POST['email_id']);
                $this->newsroom->{$_POST['_type']}($email);
                UNL_ENews_Controller::redirect(UNL_ENews_Controller::getURL().'?view=newsroom');
                break;
        }
    }

    function __get($var)
    {
        return $this->newsroom->$var;
    }

    function __call($method, $params)
    {
        return call_user_func_array(array($this->newsroom, $method), $params);
    }
}

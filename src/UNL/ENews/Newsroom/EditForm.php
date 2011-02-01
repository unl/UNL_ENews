<?php
class UNL_ENews_Newsroom_EditForm extends UNL_ENews_LoginRequired
{
    public $newsroom;

    function __postConstruct()
    {
        $this->newsroom = UNL_ENews_Newsroom::getById(UNL_ENews_Controller::getUser(true)->newsroom_id);

        if (false === $this->newsroom) {
            throw new Exception('Your newsroom doesn\'t exist!');
        }

        if (!UNL_ENews_Controller::getUser(true)->hasNewsroomPermission($this->newsroom->id)) {
            throw new Exception('Your newsroom is one that you don\'t have permission to.');
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
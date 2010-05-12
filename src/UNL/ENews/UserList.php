<?php
class UNL_ENews_UserList extends ArrayIterator
{
    /**
     * @return UNL_ENews_User
     */
    function current()
    {
        return new UNL_ENews_User(array('uid'=>parent::current()));
    }
}
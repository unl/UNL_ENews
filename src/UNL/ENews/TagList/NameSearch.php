<?php
class UNL_ENews_TagList_NameSearch extends UNL_ENews_TagList
{
    public $options = array('q'=>null);

    function getSQL()
    {
        if (empty($this->options['q'])) {
            throw new UnexpectedValueException('You must supply a search term', '400');
        }

        $mysql = UNL_ENews_Controller::getDB();
        return 'SELECT * FROM tags WHERE name LIKE "%'.$mysql->escape_string($this->options['q']).'%" ORDER BY name';
    }
}
<?php
abstract class UNL_ENews_TagList extends ArrayIterator
{
	public $options = array();

	function __construct($options = array())
	{

		$this->options = $options + $this->options;

        $tags   = array();
        $sql    = $this->getSQL();
        $mysqli = UNL_ENews_Controller::getDB();
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $tags[] = $row[0];
            }
        }
        parent::__construct($tags);
	}

	/**
	 * Return the SQL necessary to retrieve this list of tags
	 *
	 * @return string
	 */
	abstract function getSQL();

    /**
     * @return UNL_ENews_Tag
     */
    function current()
    {
        return UNL_ENews_Tag::getByID(parent::current());
    }
}
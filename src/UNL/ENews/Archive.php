<?php
class UNL_ENews_Archive extends LimitIterator implements Countable
{
	public $newsroom;
	public $options = array('limit'=>10, 'offset'=>0);
	
    function __construct($options = array())
    {
        if (!isset($options['shortname'])) {
            throw new Exception('No shortname was provided.');
        }

        $this->options = $options + $this->options;

        $this->newsroom = UNL_ENews_Newsroom::getByShortname($this->options['shortname']);
        $newsletters = $this->newsroom->getNewsletters();
        parent::__construct($newsletters, $this->options['offset'], $this->options['limit']);
    }

    function count()
    {
        $iterator = $this->getInnerIterator();
        if ($iterator instanceof EmptyIterator) {
            return 0;
        }

        return count($this->getInnerIterator());
    }
    
    static function getByShortName($shortname)
    {
        $options = array('shortname' => $shortname);
        return new self($options);
    }
}
<?php
class UNL_ENews_StoryList extends LimitIterator implements Countable
{
    function __construct($stories, $offset = 0, $count = -1)
    {
        if (count($stories) == 0) {
            $iterator = new EmptyIterator();
        } else {
            $iterator = new ArrayIterator($stories);
        }
        parent::__construct($iterator, $offset, $count);
    }

    function count()
    {
        $iterator = $this->getInnerIterator();
        if ($iterator instanceof EmptyIterator) {
            return 0;
        }

        return count($this->getInnerIterator());
    }

    /**
     * @return UNL_ENews_Story
     */
    function current()
    {
        $story = UNL_ENews_Story::getByID(parent::current());

        if (!$story) {
            throw new Exception('The story with id of '.(int)parent::current().' does not exist!');
        }

        return $story;
    }
}
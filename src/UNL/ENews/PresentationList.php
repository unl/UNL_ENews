<?php
class UNL_ENews_PresentationList extends LimitIterator implements Countable
{
    function __construct($presentations, $offset = 0, $count = -1)
    {
        if (count($presentations) == 0) {
            $iterator = new EmptyIterator();
        } else {
            $iterator = new ArrayIterator($presentations);
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
     * @return UNL_ENews_Story_Presentation
     */
    function current()
    {
        return UNL_ENews_Story_Presentation::getByID(parent::current());
    }
}
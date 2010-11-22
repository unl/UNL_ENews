<?php
class UNL_ENews_StoryList_Filter_ByPresentationType extends FilterIterator
{
    function __construct($iterator, $type)
    {
        $this->type = $type;
        parent::__construct($iterator);
        // Not sure if this relates to http://bugs.php.net/bug.php?id=52560
        $this->rewind();
    }

    function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    function accept()
    {
        if ($this->current()->presentation->type == $this->type) {
            return true;
        }
        return false;
    }
}
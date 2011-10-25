<?php
class UNL_ENews_Newsroom_Emails_Filter_ByOptOut extends FilterIterator
{
    function __construct(UNL_ENews_Newsroom_Emails $emails)
    {
        parent::__construct($emails);
    }

    function accept()
    {
        return (bool)$this->current()->optout;
    }
}
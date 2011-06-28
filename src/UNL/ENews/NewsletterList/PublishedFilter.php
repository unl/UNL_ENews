<?php
class UNL_ENews_NewsletterList_PublishedFilter extends FilterIterator
{
    function __construct(UNL_ENews_NewsletterList $list)
    {
        parent::__construct($list);
    }

    function accept()
    {
        /* @var $newsletter UNL_ENews_Newsletter */
        $newsletter = $this->current();
        if (empty($newsletter->release_date)) {
            return false;
        }
        $time = strtotime($newsletter->release_date);
        if ($time < time()) {
            return true;
        }
        return false;
    }
}
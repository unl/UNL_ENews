<?php
class UNL_ENews_Newsletter_StoryColumn
{
    protected $htmlAttributes = array();

    protected $isPreview = false;

    protected $countFilter = -1;

    protected $stories = array();

    public function __construct($options = array())
    {
        foreach ($options as $key => $option) {
            if ($key == 'filter') {
                $this->countFilter = (int)$option;
            } else if ($key == 'preview') {
                $this->isPreview = (bool)$option;
            } else if ($key == 'stories') {
                $this->stories = $option;
            } else {
                $this->htmlAttributes[$key] = $option;
            }
        }
    }

    public function getStoriesIterator()
    {
        $arrayIter = new ArrayIterator($this->stories);
        return new LimitIterator($arrayIter, 0, $this->countFilter);
    }

    public function getHtmlAttribute($attr)
    {
        if (isset($this->htmlAttributes[$attr])) {
            return $this->htmlAttributes[$attr];
        }

        return null;
    }

    public function isPreview()
    {
        return $this->isPreview;
    }
}
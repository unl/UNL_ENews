<?php
class UNL_ENews_Newsletter_StoryColumn
{
    protected $htmlAttributes = array();

    protected $isPreview = false;

    protected $isForWeb = false;

    protected $countFilter = -1;

    protected $stories = array();

    public function __construct($options = array())
    {
        foreach ($options as $key => $option) {
            switch ($key) {
                case 'filter':
                    $this->countFilter = (int)$option;
                    break;
                case 'preview':
                    $this->isPreview = (bool)$option;
                    break;
                case 'web':
                     $this->isForWeb = (bool)$option;
                     break;
                case 'stories':
                	$this->stories = $option;
                	break;
                default:
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

    public function isForWeb()
    {
        return $this->isForWeb;
    }
}
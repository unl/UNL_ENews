<?php
class UNL_ENews_Newsletter_Stories extends UNL_ENews_StoryList
{

    protected $newsletter_id;

    protected $isPreview = false;

    protected $areaCache;

    function __construct($options = array())
    {
        $this->newsletter_id = (int)$options['newsletter_id'];

        $stories = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT story_id FROM newsletter_stories ';
        $sql .= 'WHERE newsletter_id = '.$this->newsletter_id .
                ' ORDER BY `sort_order` ASC;';
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $stories[] = $row[0];
            }
        }
        $mysqli->close();
        parent::__construct($stories);
    }


    function current()
    {
        return UNL_ENews_Newsletter_Story::getById($this->newsletter_id, parent::current()->id);
    }

    /**
     * Retrieve stories from this newslettered grouped by area and column
     *
     * @param string $area [OPTIONAL] A specific area to retrieve columns for
     */
    function getStoriesByColumn($area = null)
    {
        if (empty($this->areaCache)) {
            $areas = array(
                'news' => array(
                    0 => array(),
                    1 => array(),
                    2 => array()
                ),
                'ads'  => array(
                    0 => array(),
                    1 => array(),
                    2 => array()
                )
            );

            foreach ($this as $story) {
                $areaPtr =& $areas['news'];
                if ($story->getPresentation()->type == 'ad') {
                    $areaPtr =& $areas['ads'];
                }

                $areaPtr[$story->getSortOrderOffset()][] = $story;
            }

            $this->areaCache = $areas;
        }

        if (!empty($area)) {
            return $this->areaCache[$area];
        }

        return $this->areaCache;
    }

    /**
     * Factory method for a UNL_ENews_Newsletter_StoryColumn
     *
     * @param array $options An array of options to be transformed to a StoryColumn
     */
    function getStoryColumn($stories, $options)
    {
        $columnOptions = array('stories' => $stories);

        $isPreview = false;
        if (isset($options['preview']) && $options['preview']) {
            $columnOptions['preview'] = $isPreview = true;
        }

        if (isset($options['web']) && $options['web']) {
            $columnOptions['web'] = true;
        }

        $area = isset($options['area']) ? $options['area'] : 'news';

        if ($area == 'news') {
           $prefix = 'newsColumn';
        } else {
            $prefix = 'adArea';
            if (!$isPreview) {
                $columnOptions['filter'] = 1;
            }
        }
        $columnOptions['class'] = $prefix;

        if (isset($options['offset'])) {
            switch ($options['offset']) {
                case 1:
                    $columnOptions['id'] = $prefix . 'Intro';
                    break;
                case 2:
                    $columnOptions['id'] = $prefix . '1';
                    break;
                case 0:
                    $columnOptions['id'] = $prefix . '2';
                    break;
            }
        }

        return new UNL_ENews_Newsletter_StoryColumn($columnOptions);
    }

    function setIsPreview($isPreview)
    {
        $this->isPreview = $isPreview;
    }

    function getIsPreview()
    {
        return $this->isPreview;
    }
}
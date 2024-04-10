<?php
class UNL_ENews_Newsletter_Story extends UNL_ENews_Record
{
    /**
     * The newsletter
     *
     * @var int
     */
    public $newsletter_id;

    /**
     * Id of the story
     *
     * @var id
     */
    public $story_id;

    /**
     * Id of the presentation (if null use the story's)
     *
     * @var int
     */
    public $presentation_id;

    /**
     * The order this story should be presented in
     *
     * @var int
     */
    public $sort_order;

    /**
     * Any introductory text to the story
     *
     * @var string
     */
    public $intro;

    /**
     * @property $story      UNL_ENews_Story
     * @property $newsletter UNL_ENews_Newsletter
     */

    /**
     * Constructor
     *
     * @param array $options
     *
     * @throws Exception
     */
    function __construct($options = array())
    {
        //Check to make sure the story is valid for the given newsroom.
        if (isset($options['newsletter_id'])) {
            if (!($this->newsletter = UNL_ENews_Newsletter::getById($options['newsletter_id']))) {
                throw new Exception('This newsletter does not exist', 404);
            }

            if (!($this->story = UNL_ENews_Story::getById($options['id']))) {
                throw new Exception('This story does not exist.', 400);
            }
            $this->story_id = $options['id'];

            if (!($this->newsletter->hasStory($this->story))) {
                throw new Exception('The story does not belong to the given newsletter.', 400);
            }
            if (!($this->newsroom = UNL_ENews_Newsroom::getByID($this->newsletter->newsroom_id))) {
                throw new Exception('The newsroom does not exist!', 404);
            }
            if (!($this->newsroom->shortname == $options['shortname'])) {
                throw new Exception('Not a valid newsroom name.', 400);
            }

            if (isset($this->newsroom->private_web_view) && $this->newsroom->private_web_view === '1') {
                if (empty(UNL_ENews_Controller::authenticate())) {
                    throw new Exception('You do not have access to view this newsletter page', 404);
                }                
            }
        }
    }

    public function getTable()
    {
        return 'newsletter_stories';
    }

    function keys()
    {
        return array('newsletter_id', 'story_id');
    }

    /**
     * get a story in this newsletter
     *
     * @param int $newsletter_id
     * @param int $story_id
     *
     * @return UNL_ENews_Newsletter_Story
     */
    static function getById($newsletter_id, $story_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = "SELECT * FROM newsletter_stories WHERE newsletter_id = ".intval($newsletter_id)." AND story_id = ".intval($story_id);
        if (($result = $mysqli->query($sql))
            && $result->num_rows > 0) {
            $object = new self();
            $object->synchronizeWithArray($result->fetch_assoc());
            return $object;
        }
        return false;
    }

    function getStory()
    {
        return UNL_ENews_Story::getById($this->story_id);
    }

    /**
     * Get the presentation for this newsletter story, falls back to
     * the presentation of the story
     *
     * @return UNL_ENews_Story_Presentation
     */
    public function getPresentation()
    {
        if (null !== $this->presentation_id) {
            return UNL_ENews_Story_Presentation::getByID($this->presentation_id);
        }

        return $this->getStory()->getPresentation();

    }

    /**
     * Sets the presentation of this newletter story
     *
     * @param UNL_ENews_Story_Presentation|int $presentation
     */
    public function setPresentation($presentation)
    {
        if ($presentation instanceof UNL_ENews_Story_Presentation) {
            $this->presentation_id = $presentation->id;
            return $this;
        }

        if (UNL_ENews_Story_Presentation::getByID($presentation)) {
            $this->presentation_id = $presentation;
            return $this;
        }

        throw new Exception('Invalid presentation');
    }

    /**
     * Gets the template path for the story presentation
     *
     * @param string $prefix [OPTIONAL]
     */
    public function getRenderer($prefix = '')
    {
        if (!empty($prefix)) {
            $prefix = rtrim($prefix, '/') . '/';
        }

        /* Do we need to abstract entire templates for each column?
        $path = $prefix . 'ENews/Newsletter/Story/Presentation/' . $col . '/' . $this->getPresentation()->template;
        if ($savvy->findTemplateFile($path)) {
            return $path;
        }
        */

        $path = $prefix . 'ENews/Newsletter/Story/Presentation/' . $this->getPresentation()->template;
        return $path;
    }

    /**
     * Gets the string representation of the story's column width
     * @return string twocol|onecol
     */
    public function getColFromSort()
    {
        $offset = $this->getSortOrderOffset();

        switch ($offset) {
            case 1:
                return 'twocol'; //Full-Width
            case 2:
            case 0:
                return 'onecol'; //Half-Width
        }

        throw new UnexpectedValueException('That offset is unknown. I don\'t know which column I\'m supposed to be in.', 500);
    }

    public function getSortOrderOffset()
    {
        return $this->sort_order % 3;
    }

    public function getURL()
    {
        return $this->newsletter->getURL() . '/' . $this->story_id;
    }

    function __call($method, $params)
    {
        switch($method) {
            case 'getThumbnail':
            case 'getFiles':
            case 'getFileByUse':
            case 'hasNotExpired':
            case 'getURL':
                return call_user_func_array(array($this->getStory(), $method), $params);
        }

        $trace = debug_backtrace();
        $message = 'Undefined method via __call(): ' . $method;
        if (isset($trace[0]['file'])) {
            $message .= ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'];
        }
        trigger_error($message, E_USER_NOTICE);
        return null;
    }

    function __get($var)
    {
        switch($var){
            case 'story':
                return $this->getStory();
            case 'newsroom':
                return UNL_ENews_Newsroom::getByID($this->newsletter->newsroom_id);
            case 'newsletter':
                return UNL_Enews_newsletter::getByID($this->newsletter_id);
            default:
                return $this->getStory()->$var;
        }
    }

    function __isset($var)
    {
        return isset($this->getStory()->$var);
    }
}

<?php
class UNL_ENews_Newsletter_Preview_Story extends UNL_ENews_LoginRequired
{
    /**
     * The newsletter
     *
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;

    /**
     * The story
     *
     * @var UNL_ENews_Newsletter_Story
     */
    public $story;

    function __postConstruct()
    {
        if (!isset($this->options['id'], $this->options['story_id'])) {
            throw new Exception('Invalid Request. You must send a newsletter id and story_id', 400);
        }
        $this->newsletter = UNL_ENews_Newsletter::getById($this->options['id']);
        if (!$this->newsletter) {
            throw new Exception('Invalid Request. No newsletter matches that id.', 404);
        }

        $this->story = UNL_ENews_Newsletter_Story::getById($this->newsletter->id, $this->options['story_id']);
        if (!$this->story) {
            throw new Exception('Invalid Request. No story exists with that id.', 404);
        }
    }
}
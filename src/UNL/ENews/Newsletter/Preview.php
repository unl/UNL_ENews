<?php
class UNL_ENews_Newsletter_Preview extends UNL_ENews_LoginRequired
{
    /**
     * The newsletter
     *
     * @var UNL_ENews_Newsletter
     */
    public $newsletter;

    public $available_stories;

    function __postConstruct()
    {
        if (isset($this->options['id'])) {
            $this->newsletter = UNL_ENews_Newsletter::getById($this->options['id']);
        } else {
            $this->newsletter = UNL_ENews_Newsletter::getLastModified();
        }
        if (!empty($_POST)) {
            $this->handlePost();
        }
        $this->available_stories = new UNL_ENews_StoryList_Filter_ByPresentationType(
            new UNL_ENews_Newsroom_UnpublishedStories(array(
                'status'      => 'approved',
                'date'        => $this->newsletter->release_date,
                'newsroom_id' => UNL_ENews_Controller::getUser(true)->newsroom->id,
                'limit'       => -1
            )),
            'news'
        );
    }

    function handlePost()
    {
        $this->filterPostValues();
        switch($_POST['_type']) {
            case 'addstory':
                if (!isset($_POST['story_id'])) {
                    throw new Exception('invalid data, you must set the story_id', 400);
                }
                if (is_array($_POST['story_id'])) {
                    foreach ($_POST['story_id'] as $id => $def) {
                        $this->addStory($id, $def['sort_order']);
                    }
                } else {
                    $this->addStory($_POST['story_id'], $_POST['sort_order'], $_POST['intro']);
                }
                break;
            case 'setpresentation':
                if (!isset($_POST['story_id'], $_POST['presentation_id'])) {
                    throw new Exception('invalid request', 400);
                }
                $this->setPresentation($_POST['story_id'], $_POST['presentation_id']);
                break;
            case 'removestory':
                if (!isset($_POST['story_id'])) {
                    throw new Exception('invalid data, you must set the story_id', 400);
                }
                $this->removeStory($_POST['story_id']);
                break;
            case 'newsletter':
                UNL_ENews_Controller::setObjectFromArray($this->newsletter, $_POST);
                $this->newsletter->save();
                UNL_ENews_Controller::redirect(UNL_ENews_Controller::getURL().'?view=preview&id='.$this->newsletter->id);
                break;
        }
        // no response is needed (AJAX'd)
        exit();
    }

    function filterPostValues()
    {
        unset($_POST['newsroom_id']);
    }

    function removeStory($story_id)
    {
        if ($story = UNL_ENews_Story::getById($story_id)) {
            return $this->newsletter->removeStory($story);
        }
        return true;
    }

    function addStory($story_id, $sort_order = null, $intro = null)
    {
        if ($story = UNL_ENews_Story::getById($story_id)) {
            return $this->newsletter->addStory($story, $sort_order, $intro);
        }
        throw new Exception('could not add the story to the newsletter', 500);
    }

    function setPresentation($story_id, $presentation_id)
    {
        if ($story = UNL_ENews_Newsletter_Story::getById($this->newsletter->id, $story_id)) {
            $story->setPresentation($presentation_id);
            return $story->save();
        }
        throw new Exception('Could not save presentation', 500);
    }
}
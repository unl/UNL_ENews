<?php
class UNL_ENews_Newsroom_Stories extends UNL_ENews_Newsroom_StoryList
{

    function getSQL()
    {
        $sql = 'SELECT newsroom_stories.story_id FROM newsroom_stories, stories ';
        $sql .= 'WHERE newsroom_stories.newsroom_id = '.(int)$this->options['newsroom_id'] .
                        ' AND newsroom_stories.status = \''.$this->options['status'].'\' AND newsroom_stories.story_id = stories.id';
        switch($this->options['status']) {
            case 'archived':
                $sql .= ' ORDER BY stories.date_submitted DESC';
                break;
            case 'approved':
                $sql .= ' ORDER BY stories.request_publish_start ASC';
                break;
        }
        return $sql;
    }

    public static function relationshipExists($newsroom_id,$story_id)
    {
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = 'SELECT newsroom_id,story_id FROM newsroom_stories ';
        $sql .= 'WHERE newsroom_id = '.(int)$newsroom_id .
                ' AND story_id = '.(int)$story_id;
        if ($result = $mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                return true;
            }
        }
        return false;
    }

    public function getManageURL($additional_params = array())
    {
        $url = $this->newsroom->getURL().'/manage';
        return UNL_ENews_Controller::addURLParams($url, $additional_params);
    }
}
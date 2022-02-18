<?php
class UNL_ENews_Newsroom_Stories extends UNL_ENews_Newsroom_StoryList
{
    function getSQL()
    {
        $sql = $this->getNewsroomSQL();
        $sql .= ' AND newsroom_stories.status = ? AND newsroom_stories.story_id = stories.id';
        $termFiltered = false;
        if (!empty($this->options['term']) && strlen($this->options['term']) >= 3) {
            $termFiltered = true;
            $sql .= ' AND (stories.title like ? OR stories.description like ? OR stories.full_article like ?)';
        }
        switch($this->options['status']) {
            case 'archived':
                $sql .= ' ORDER BY stories.date_submitted DESC';
                break;
            case 'approved':
                $sql .= ' ORDER BY stories.request_publish_start ASC';
                break;
        }

        $mysqli = UNL_ENews_Controller::getDB();
        $stmt = $mysqli->prepare($sql);
        if ($termFiltered) {
            $likeTerm = '%' . $this->options['term'] . '%';
            $stmt->bind_param('ssss', $this->options['status'], $likeTerm, $likeTerm, $likeTerm);
        } else {
            $stmt->bind_param('s', $this->options['status']);
        }
        return $stmt;
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
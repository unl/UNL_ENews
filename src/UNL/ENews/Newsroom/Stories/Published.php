<?php
class UNL_ENews_Newsroom_Stories_Published extends UNL_ENews_Newsroom_StoryList
{
    function getSQL()
    {
        $sql = $this->getNewsroomSQL();
        $sql .= ' AND newsroom_stories.status != "pending" AND stories.request_publish_start < "'.date('Y-m-d H:i:s').'"
            ORDER BY stories.request_publish_start DESC;';
        return $sql;
    }
}
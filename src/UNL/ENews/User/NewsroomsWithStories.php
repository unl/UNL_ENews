<?php
/**
 * Collection of newsrooms a user has submitted stories to.
 * 
 * <code>
 * $newsrooms = new UNL_ENews_User_NewsroomsWithStories(array('uid'=>'bbieber2'));
 * </code>
 * 
 * @see UNL_ENews_User::getNewsroomsWithStories()
 */
class UNL_ENews_User_NewsroomsWithStories extends UNL_ENews_NewsroomList
{
    function __construct($options = array())
    {
        $newsroom_ids = array();
        $mysqli = UNL_ENews_Controller::getDB();
        $sql = '
            SELECT count(newsroom_id) AS story_count, newsroom_id
            FROM newsroom_stories
                WHERE uid_created = "'.$mysqli->escape_string($options['uid']).'"
            GROUP BY newsroom_id ORDER BY story_count DESC';
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $newsroom_ids[] = $row['newsroom_id'];
            }
        }
        parent::__construct($newsroom_ids);
    }
}
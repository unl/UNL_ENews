<?php
class UNL_ENews_Tags extends UNL_ENews_TagList
{
	function getSQL()
	{
		return 'SELECT * FROM tags ORDER BY name';
	}
}
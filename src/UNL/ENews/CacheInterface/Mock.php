<?php
class UNL_UnderGraduateBulletin_CacheInterface_Mock implements UNL_ENews_CacheInterface
{
    function get($key)
    {
        // Expired cache always.
        return false;
    }
    
    function save($key, $data)
    {
        // Make it appear as though it was saved.
        return true;
    }
}
?>